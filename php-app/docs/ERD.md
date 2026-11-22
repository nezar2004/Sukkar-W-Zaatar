# M3 — Entity-Relationship Diagram and Relational Schema

```mermaid
erDiagram
 USERS ||--o{ ORDERS : places
 CATEGORIES ||--o{ PRODUCTS : contains
 ORDERS ||--|{ ORDER_ITEMS : includes
 PRODUCTS ||--o{ ORDER_ITEMS : references
 USERS { int id PK
 string name
 string email UK
 string password_hash
 enum role }
 CATEGORIES { int id PK
 string name UK }
 PRODUCTS { int id PK
 int category_id FK
 string name
 decimal price
 int stock
 bool active }
 ORDERS { int id PK
 int user_id FK
 string customer_name
 decimal total
 enum status }
 ORDER_ITEMS { int id PK
 int order_id FK
 int product_id FK
 int quantity
 decimal unit_price }
 CONTACT_MESSAGES { int id PK
 string name
 string email
 text message }
```

- PKs are auto-increment unsigned integers. Unique keys: users.email, categories.name and (order_id, product_id) in order_items.
- Foreign keys: products.category_id → categories.id; orders.user_id → users.id; order_items.order_id → orders.id; order_items.product_id → products.id.
- Orders and items are separate tables so one order contains multiple products with snapshot unit prices. This avoids repeating product data and preserves checkout history.
- Contact messages are independent submissions and do not require user accounts.
- Price is `DECIMAL(10,2)` and quantity/stock are unsigned integers. Checks apply to prices and order item quantities.
- Referential integrity and consistent stock changes are enforced by InnoDB transaction boundaries.

See exact executable schema in `database/schema.sql` and initial dataset in `database/seed.sql`.
