# Test Plan (M4)

These are **manual test cases with expected results**. Actual outcomes must be recorded after running the project with MySQL; none are falsely marked as executed.

| ID | Workflow | Steps | Expected result | Actual result | Status |
|---|---|---|---|---|---|
| T01 | Menu browse | Open menu with database seeded | Product cards load; no inactive items | Not run | Pending |
| T02 | Filter | Select Cakes category | Only cakes appear | Not run | Pending |
| T03 | Search | Search for "brownie" | Matching product returned | Not run | Pending |
| T04 | Registration | Create customer using unused email, 8+ char password | Account created and signed in | Not run | Pending |
| T05 | Bad registration | Invalid email or short password | Server rejects submission with message | Not run | Pending |
| T06 | Login | Login using correct and incorrect credentials | Correct succeeds, invalid fails | Not run | Pending |
| T07 | Cart | Add a product, update qty to 2, remove item | Cart count and totals recalculate | Not run | Pending |
| T08 | Checkout auth | Try checkout when signed out | Redirected to login | Not run | Pending |
| T09 | Order placement | Submit details with item in stock | Order and items created; inventory deducted | Not run | Pending |
| T10 | Low-stock block | Order more units than current stock | Order rejected, inventory unchanged | Not run | Pending |
| T11 | User isolation | Customer A tries to see Customer B orders | Only A's own orders displayed | Not run | Pending |
| T12 | Admin auth | Customer visits admin route or POSTs admin action | Access forbidden | Not run | Pending |
| T13 | Product CRUD | Admin adds, edits and removes sample product | All changes persist, deleted product hidden | Not run | Pending |
| T14 | Order status | Admin sets order to preparing | Customer views new status | Not run | Pending |
| T15 | Contact | Visitor sends valid message | Message stored and visible to admin | Not run | Pending |
| T16 | CSRF | Submit POST without token | Rejected with HTTP 419 | Not run | Pending |
| T17 | SQL injection | Search text `' OR 1=1 --` | Treated as literal; no SQL manipulation | Not run | Pending |
| T18 | XSS | Submit HTML as a contact message | HTML escaped in admin display | Not run | Pending |
| T19 | Responsiveness | Test at 390px, 768px and 1440px | No missing actions; navigation usable | Not run | Pending |
| T20 | Payment boundary | Submit only supported checkout method | Unsupported methods rejected on backend | Not run | Pending |

**Execution record:** Tester: __________ Date: __________ PHP version: __________ MySQL version: __________ Browser: __________

**Bug log template:** Bug ID / Test ID / Reproduction steps / Expected / Actual / Severity / Fix / Retest result.
