# User Guide

## Visitor
Open Home to see bakery overview, menu and story. Choose **Our Menu**, click a category or use the search box, then select a product for its description and price. Use **Add to bag** to start an order.

## Customer
1. In **Bag**, adjust quantities with **Update** or remove products.
2. Continue to checkout. Sign in or register if needed.
3. Enter full name, phone and delivery address. Pick cash-on-delivery or store pickup.
4. Confirm **Place your order**. View the order under **My Orders**.
5. Monitor the status (pending, preparing, ready, completed, cancelled).
6. To sign out, open My Orders and choose **Sign out**.

## Administrator
1. Use the admin credentials you created with the CLI script.
2. Select **Dashboard**. Overview shows active products, orders, new orders and completed revenue.
3. Under Products, create a new item or click Edit. **Remove** performs a soft deletion; historical order records are kept.
4. Under Orders, change a status and click Save.
5. Under Messages, read submitted contact questions.

## Common errors
- Invalid credentials: verify account email and password.
- Insufficient stock: reduce quantity; the stock might have changed since the cart was prepared.
- Missing config: copy `config.example.php` to `config.php` and set MySQL credentials.
- Database error: confirm MySQL service is running and SQL was imported.

## Accessibility
Use labels and native input validation; keyboard Tab moves between interactive elements. The mobile menu has an accessible button label and expanded state.
