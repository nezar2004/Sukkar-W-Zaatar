const { createServer } = require('node:http');

const origin = process.env.STOREFRONT_ORIGIN || 'https://nezar2004.github.io';
const configured = Boolean(process.env.FIREBASE_SERVICE_ACCOUNT || process.env.FIRESTORE_EMULATOR_HOST);
const api = configured ? require('./index.js') : {};
const auth = configured ? require('firebase-admin/auth').getAuth() : null;
const codeStatus = { 'invalid-argument': 400, unauthenticated: 401, 'permission-denied': 403, 'not-found': 404, 'failed-precondition': 409, 'resource-exhausted': 429 };

function send(response, status, data) {
  response.writeHead(status, { 'Content-Type': 'application/json', 'Cache-Control': 'no-store' });
  response.end(JSON.stringify(data));
}

async function parseBody(request) {
  let size = 0;
  const parts = [];
  for await (const part of request) {
    size += part.length;
    if (size > 16000) throw new Error('Body exceeds size limit.');
    parts.push(part);
  }
  return JSON.parse(Buffer.concat(parts).toString());
}

const server = createServer(async (request, response) => {
  if (request.url === '/health') return send(response, 200, { configured });
  if (request.headers.origin === origin) {
    response.setHeader('Access-Control-Allow-Origin', origin);
    response.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
    response.setHeader('Access-Control-Allow-Headers', 'content-type, authorization');
    response.setHeader('Vary', 'Origin');
  }
  if (request.method === 'OPTIONS') {
    response.writeHead(request.headers.origin === origin ? 204 : 403).end();
    return;
  }
  if (!configured) return send(response, 503, { error: 'Online ordering is not configured.' });
  const handler = request.url?.startsWith('/api/') && Object.hasOwn(api, request.url.slice(5)) && api[request.url.slice(5)];
  if (request.method !== 'POST' || !handler) return send(response, 404, { error: 'Not found.' });
  let identity;
  const bearer = request.headers.authorization;
  if (bearer) {
    try {
      if (!bearer.startsWith('Bearer ')) throw new Error('Invalid authorization');
      const token = await auth.verifyIdToken(bearer.slice(7));
      identity = { uid: token.uid, token };
    } catch {
      return send(response, 401, { error: 'Your session expired. Sign in and try again.' });
    }
  }
  let data;
  try { data = await parseBody(request); }
  catch { return send(response, 400, { error: 'Invalid request.' }); }
  try {
    return send(response, 200, { result: await handler({ data, auth: identity }) });
  } catch (error) {
    const status = codeStatus[error.code];
    if (!status) console.error(error);
    return send(response, status || 500, { error: status ? error.message : 'Could not complete the request. Try again.' });
  }
});

server.listen(Number(process.env.PORT || 5001), '0.0.0.0');
