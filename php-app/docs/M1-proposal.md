# M1 — Project Proposal & Requirements (Week 4 · 5 marks)

## Business and problem statement
**Business:** Sukkar & Zaatar, a fictional neighborhood bakery in Irbid, Jordan that mirrors challenges often found in small local shops.

**Current problem:** Phone messages, handwritten order notes, disconnected stock spreadsheets and scattered customer contacts make it hard to track orders, maintain accurate inventory, and offer online ordering. These disconnected workflows can cause errors, slow response times and missed sales.

**Solution:** A centralized web application connecting a public product catalog and online ordering flow to an authenticated administrative interface and relational data storage.

## Measurable objectives
1. Offer a responsive searchable menu with categories, item descriptions and prices.
2. Enable registered customers to place orders and view their statuses.
3. Allow a store manager to perform product CRUD (including soft deletion) and update orders.
4. Store products, orders, order items and messages in normalized relational tables with foreign keys.
5. Prevent typical invalid submissions using frontend + backend checks and prepared SQL statements.

## In scope
Product catalog, category filtering/search, product details, shopping bag, account registration and sign in, cash-on-delivery/pickup checkout, order history, contact form, admin dashboard, product CRUD, order status updates, stock deduction, database and testing documents.

## Out of scope
Actual payment gateway and financial settlement, courier GPS tracking, automated emails/SMS, real business licensing, multi-branch accounting, loyalty points, advanced demand forecasting and mobile apps.

## Target users
- **Visitor:** Discover store details, menu and products; contact the shop.
- **Customer:** Register/sign in, order, view order history and fulfillment status.
- **Administrator:** Maintain products/stock, review customer messages, update order progress and see basic store metrics.

## Functional requirements
FR01 Browse active products and see detail pages. FR02 Search and filter by category. FR03 Register and sign in. FR04 Add/update/remove items in cart. FR05 Create transactional order and deduct stock. FR06 View only own order history. FR07 Submit a contact message. FR08 Admin CRUD for products. FR09 Admin status update for orders. FR10 Admin read contact messages and overview statistics. FR11 Sign out securely.

## Nonfunctional requirements
NFR01 Mobile-first responsive UI with consistent typography and navigation. NFR02 Escape all dynamic HTML. NFR03 Validate POST requests and use parameterized SQL. NFR04 Enforce authorization server-side for protected routes. NFR05 Preserve data integrity with foreign keys and checkout transactions. NFR06 Provide understandable validation and feedback states. NFR07 Keep sensitive database credentials outside version control. NFR08 Use intuitive, accessible labels and landmarks.

## Use cases
UC01 Visitor browses menu → searches/filter → opens product. UC02 Customer creates account → adds products → submits order → sees confirmation. UC03 Customer signs in → checks order status. UC04 Administrator signs in → edits product → confirms catalog change. UC05 Administrator reviews orders → moves status from pending to preparing to ready. UC06 Visitor sends question → admin reads message.

## Initial plan
| Week | Work |
|---|---|
| 1–2 | Interview local-shop representative or conduct a simulated discovery; identify problem and actors |
| 3–4 | Finalize scope, functional/nonfunctional requirements, user flows and M1 proposal |
| 5–7 | Sitemap, mockups, navigation, responsive frontend and M2 demo |
| 8–10 | Database design, normalized schema, sample SQL and application connection |
| 11–12 | CRUD, authentication, transactions, validation and M3 demo |
| 13–14 | Full user workflow tests, security and UX review, fixes, documentation |
| 15 | Final source code, presentation and live demonstration |

**Team (3–4):** Assign a member to requirements/design, frontend, backend/database and testing/docs; if three members, combine testing with frontend. Add student names and IDs before submission.

**Evidence caveat:** User research is a proposed activity; no real interviews have been claimed.
