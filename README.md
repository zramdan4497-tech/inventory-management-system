# Inventory Management System (PHP + MySQL)

A complete inventory and order management app for a small business: secure
login, product CRUD with stock tracking, low-stock alerts, and order status
management. Built with plain PHP (PDO) and MySQL, no frameworks.

## Features

- **Authentication:** session login with `password_hash()` / `password_verify()`,
  session regeneration on sign-in
- **Dashboard:** product count, stock value at cost and sale price, low-stock
  alerts, recent orders
- **Products:** add, edit, delete (with confirmation), search by name or SKU,
  low-stock rows highlighted
- **Orders:** list with line-item totals, status updates (pending, shipped,
  delivered, cancelled)
- **Security:** PDO prepared statements everywhere, all output escaped with
  `htmlspecialchars()`

## Tech stack

- **Backend:** PHP 8 (PDO, sessions)
- **Database:** MySQL 8 (users, products, orders, order_items)
- **Frontend:** HTML, CSS, a little vanilla JavaScript

## Project layout

```
login.php / logout.php   authentication
index.php                dashboard (KPIs, low stock, recent orders)
products.php             product list, search, add, delete
edit.php                 edit a product
orders.php               order list + status updates
setup.php                one-time admin creation (run once, then delete)
db.php / auth.php        shared connection + session guard
schema.sql               database schema + seed data
app.js / style.css       frontend assets
```

## Run it locally

1. Install XAMPP (or any PHP 8 + MySQL stack) and start Apache and MySQL.
2. Copy this folder into `htdocs` (e.g. `C:\xampp\htdocs\inventory`).
3. In phpMyAdmin, run `schema.sql` (creates `inventory_db` with demo data).
4. Open `http://localhost/inventory/setup.php` once to create the admin user
   (`admin` / `admin123`), then delete `setup.php`.
5. Sign in at `http://localhost/inventory/`.

## Notes

- Seed data is synthetic and for demonstration only.
- Change the default admin password after first login.
- Edit `db.php` if your MySQL credentials differ from the XAMPP defaults.
