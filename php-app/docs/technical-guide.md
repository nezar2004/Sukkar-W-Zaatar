# Technical Guide and Final Demo Checklist

## Architecture
The app uses a PHP front controller (`index.php`) to whitelist routes and render page templates. `includes/bootstrap.php` loads configuration and common helpers. `includes/actions.php` receives POST actions and mutates database state after CSRF validation. MySQL via PDO stores users, categories, products, orders, order items and contact messages. Cart quantities are stored in the session; checkout prices and stock are re-read from the database.

## Security mechanisms
- Passwords are hashed by `password_hash` and checked with `password_verify`; session ID rotates after login/register.
- HTML is escaped with `htmlspecialchars` using UTF-8 and appropriate flags.
- All database queries with user-controlled values use PDO prepared statements.
- All POST forms include per-session CSRF tokens verified with `hash_equals`.
- Access controls run in PHP for orders and admin operations; role is never accepted from user registration.
- On checkout, rows are selected with `FOR UPDATE` under a transaction, validated, inserted and decremented atomically.
- Error handler logs server errors and presents generic output rather than leaking secrets.

## Limitations / opportunities for improvement
- Real payment gateway, order cancellation inventory reconciliation and automated notifications are beyond prototype scope.
- Login throttling, password reset, system administration audit logs, unit/integration CI and localization should be added before production release.
- Product photos are mapped to curated external image URLs rather than uploads.

## Suggested presentation order (8–10 minutes)
1. Problem: small local bakery's manual orders and scattered inventory.
2. Objectives, actor roles and sitemap.
3. Live home and responsive menu (filter/search).
4. Sign up and sign in as a customer; add two products and edit cart.
5. Complete checkout and show My Orders; confirm MySQL order and items.
6. Admin dashboard: metrics, create/edit/soft-delete products, update order status, read messages.
7. Explain ERD foreign keys, transactional inventory, CSRF, prepared statements, security rules.
8. Review tests, known limitations and future features.

## Lecturer deliverables checklist
- [x] Problem, objectives, scope, actors and requirements drafted
- [x] Sitemap, wireframes, design system and responsive frontend source
- [x] ERD, relational schema, constraints, sample database script
- [x] PHP/MySQL connection code and CRUD code
- [x] Security validation code
- [x] Test plan, user guide and technical guide
- [ ] Run/import/test application on demo computer
- [ ] Capture desktop and mobile interface screenshots
- [ ] Record actual test results with date and team member
- [ ] Insert students' names/IDs and prepare live presentation
