# Firebase orders on the free plan

The HTML storefront stays on GitHub Pages. Firebase Authentication and Firestore store identities, orders and inventory. A free Render web service validates prices and adjusts stock in a single Firestore transaction.

- The server uses the published catalog IDs and computes amounts in integer cents. Checkout retries use the same request key while the page stays open.
- Tracking links carry a high-entropy token in a URL fragment. The response contains only the order ID, fulfilment method and status.
- Staff sign in by email link. The API checks the verified address against the configured staff email.
- Browser access to Firestore is denied. Keep the Render service-account credential in a secret environment variable and grant it only the datastore role needed by this project.

## Deployment

Enable anonymous sign-in and email-link sign-in in Firebase, authorize the storefront domain, provision Firestore and deploy the repository's Firestore rules. Configure a free Render Node service with build command `npm ci --prefix functions` and start command `npm start --prefix functions` spelled as shown, plus:

| Name | Value |
| --- | --- |
| FIREBASE_SERVICE_ACCOUNT | The dedicated service account's JSON credential, stored as a secret |
| BAKERY_STAFF_EMAIL | The staff Google account address |
| STOREFRONT_ORIGIN | The HTTPS origin of the storefront |

Set the public Firebase web app configuration and the Render HTTPS address in `assets/firebase-config.js` after checking the service. Until both are ready, the storefront uses its existing browser-only demo.
