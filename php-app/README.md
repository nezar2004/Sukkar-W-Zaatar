# Sukkar & Zaatar ✳ — BIT481 Web Application Development 2

A responsive e-commerce and bakery management web application concept for a local business in **Irbid, Jordan**. Academic project for Yarmouk University, Fall 2026/2027. Business, products, branding and hours are fictional demonstration data.

## Features
- 12 navigable routes: Home, Menu, Product Detail, Cart, Checkout, My Orders, About, Contact, Sign In, Register, Admin, 404.
- Responsive, custom-designed frontend built with semantic HTML, CSS and vanilla JavaScript.
- PHP 8.1+ server-side application connected to MySQL 8+ / compatible MariaDB via PDO.
- Customers can register/sign in, browse and search products, filter categories, build carts, place cash-on-delivery/pickup orders and track status.
- Admin dashboard to view metrics and messages, create/edit/soft-delete products, and update order statuses.
- Backend validation, prepared statements, CSRF tokens, password hashes, access controls, session ID rotation, output escaping and transactional stock updates.
- Sample dataset, ERD, milestone documents, test plan and setup notes included.

## Instant design preview (no server needed)
Open `preview.html` in any modern browser. It shows the design, mobile navigation, menu filters and a demo bag counter. **It does not store orders or connect to MySQL**; use the main PHP app for the real workflows.

## Quick start with XAMPP (Windows)
1. Install XAMPP with PHP 8.1+ (PDO MySQL and mbstring enabled) and MySQL/MariaDB. Start **Apache** and **MySQL**.
2. Extract project folder inside `C:\xampp\htdocs\sukkar-zaatar`.
3. Using phpMyAdmin, run `database/schema.sql` then `database/seed.sql` (Import tab or SQL tab). **Only import the seed once** for the initial dataset; re-running it resets sample product stock and prices.
4. Copy `config.example.php` to `config.php`. Edit the database username/password if needed. **Do not commit `config.php`**.
5. Open a terminal in the project directory and run:
   `php scripts-create-admin.php admin@example.com 'ReplaceWithAUniqueSecurePassword123!'`
   Use your own email and unique password. Do not use the sample literal password above.
6. Visit `http://localhost/sukkar-zaatar/index.php`. Sign in as the admin to manage the shop; register a different account to test customer flows.

For PHP's built-in server: after starting MySQL and importing SQL, from the root run `php -S localhost:8000`, then visit `http://localhost:8000/index.php`.

## Order tracking and updates

- For an existing database, re-run `database/schema.sql` to add the tracking table. Keep the existing data and skip the seed.
- New orders generate a private tracking link. It shows the order number and status in a fresh browser session. My Orders requires sign-in and continues to show earlier orders.
- Staff can open Admin → Orders, filter by status and update a customer’s order. Tracking shows the latest status when refreshed.
- Deploy the PHP version on a PHP-capable HTTPS server backed by MySQL or MariaDB. The GitHub Pages storefront is a separate static demo.

## Important operational notes
- Images are provided by remote Unsplash URLs and Google Fonts; internet connectivity improves appearance. The application itself and its data operations run locally.
- **Online card payments are intentionally out of scope**; the demo supports cash on delivery and store pickup only. No card credentials are collected.
- Pickup requires no delivery address. The final review lists items, estimated delivery and total before confirmation. Checkout rechecks the reviewed items, prices and stock in the database.
- No mail is sent: contact messages are stored in the database and shown in the admin panel.
- Order cancellation does **not** automatically restock inventory in this academic prototype. Inventory adjustments must be handled by an administrator. Add a consistent restock procedure before production use.
- Stock changes use a SQL transaction and `SELECT ... FOR UPDATE` to prevent overselling during checkout.
- Product "delete" is a soft delete (`active=0`) to preserve historical order references.
- For production: enforce HTTPS, use environment-held secrets, rate-limit login, implement account recovery, ensure backups, use a dedicated least-privilege DB account, add audit logging, configure error monitoring and obtain authorization for real commercial use.

## Folder map
- `index.php`: main router.
- `includes/`: configuration bootstrap, shared header/footer, business logic, secure POST handlers.
- `pages/`: page templates, including admin pages.
- `assets/`: full responsive stylesheet and JavaScript behavior.
- `database/`: MySQL schema and realistic sample products.
- `docs/`: project proposal, sitemap, ERD and implementation notes.
- `tests/`: manual test cases and expected results (not claimed as executed).

## Milestone mapping
- **M1:** `docs/M1-proposal.md` — problem, objectives, users, scope, requirements, use cases, timeline.
- **M2:** `docs/M2-design.md` — sitemap, responsive design, page-by-page wireframes and frontend prototype in `pages/`, `assets/`.
- **M3:** `docs/ERD.md`, `database/schema.sql`, `database/seed.sql`, PHP PDO, CRUD and validation.
- **M4:** full source code, `tests/test-plan.md`, `docs/user-guide.md`, `docs/technical-guide.md` and live demo script.

## Known boundaries
This project is a functioning PHP/MySQL implementation **once configured on a local server**. The order flow has been exercised against MariaDB, including concurrent checkout, tracking in a separate browser session and staff status updates.
