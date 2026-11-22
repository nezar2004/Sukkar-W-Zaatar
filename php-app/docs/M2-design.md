# M2 — Website Structure and Interface Design (Week 7 · 7 marks)

## Sitemap
```
Home
├── Menu
│   ├── Search and category filter
│   └── Product detail → Add to bag
├── Cart → Checkout [login required] → My Orders
├── Our Story
├── Contact
├── Sign In ↔ Register
└── Admin Dashboard [admin only]
    ├── Overview
    ├── Products [Create, Read, Update, Soft Delete]
    ├── Orders [View and change status]
    └── Messages [View]
```

## Navigation rules
- Header has Home / Our Menu / Our Story / Contact / Sign in or My Orders / Bag.
- Signed-in admins additionally see Dashboard.
- Footer repeats primary and support navigation.
- Mobile nav opens through a JavaScript toggle with `aria-expanded`.
- Breadcrumbs on product detail show context.

## Text wireframes (desktop)
```
+--------------------------- announcement ----------------------------+
| LOGO                Home Menu Story Contact            Account Bag  |
+---------------------------------------------------------------------+
| Hero headline, copy & CTAs      |  Hero pastry photography          |
| Trust message                  |  Baked-fresh badge               |
+---------------------------------------------------------------------+
| Benefits x4                                                         |
+---------------------------------------------------------------------+
| Featured products: card | card | card | card                        |
+---------------------------------------------------------------------+
| Bakery story image             | Story & CTA                       |
+---------------------------------------------------------------------+
| Footer links and details                                           |
+---------------------------------------------------------------------+
```
```
MENU: Title → [Category pills] [Search] → responsive product cards
DETAIL: [Large image] [Name, description, price, quantity, add]
CART: [Items / edit quantities] [Order summary / checkout]
CHECKOUT: [Validated delivery form, method] [Order summary]
MY ORDERS: [Order cards, timestamps, statuses]
ADMIN: [Sidebar] [Stats or products/orders/messages table & forms]
```

## Mobile behavior
- Single-column hero, product details, checkout and admin columns.
- Product grid: 2 cards on mobile, 4 on desktop, 2 on tablet.
- Benefit cards collapse 4 → 2 columns.
- Navigation changes to expandable panel; forms remain touch-friendly.
- Orders, table columns and product editing remain accessible through responsive wrapping/scrolling.

## Design system
Primary evergreen `#173E37`, warm cream `#FDFAF4`, soft clay `#D4764C`, pale blush `#F6E4DE`; editorial serif headings (Playfair Display) and clean sans typography (DM Sans). Rounded cards, restrained shadows, pill category filters, clear primary CTAs and generous whitespace.

## Prototype interaction inventory
- JS mobile menu toggle with ARIA state.
- Native required/email/min/max inputs and client-side quantity boundaries.
- Product search and category filtering through GET parameters.
- Cart updates and order submission through secure POST actions.
- Admin remove confirmation prompt.

## Mockups
The runnable page templates are high-fidelity, browser-rendered mockups and constitute the front-end prototype. For the final university submission, capture labeled desktop and mobile screenshots from the running application (do not submit blank screenshots). Helpful viewport widths: 1440px and 390px.
