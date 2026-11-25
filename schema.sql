-- Inventory Management System - schema + seed data (MySQL 8+)
DROP DATABASE IF EXISTS inventory_db;
CREATE DATABASE inventory_db CHARACTER SET utf8mb4;
USE inventory_db;

CREATE TABLE users (
    user_id    INT PRIMARY KEY AUTO_INCREMENT,
    username   VARCHAR(50) UNIQUE NOT NULL,
    pass_hash  VARCHAR(255) NOT NULL,      -- created by setup.php with password_hash()
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    product_id    INT PRIMARY KEY AUTO_INCREMENT,
    sku           VARCHAR(20) UNIQUE NOT NULL,
    product_name  VARCHAR(120) NOT NULL,
    category      VARCHAR(50)  NOT NULL,
    quantity      INT NOT NULL DEFAULT 0,
    reorder_level INT NOT NULL DEFAULT 5,
    unit_price    DECIMAL(10,2) NOT NULL,
    unit_cost     DECIMAL(10,2) NOT NULL
);

CREATE TABLE orders (
    order_id      INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    status        ENUM('pending','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id   INT NOT NULL,
    product_id INT NOT NULL,
    quantity   INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_oi_order   FOREIGN KEY (order_id)   REFERENCES orders(order_id),
    CONSTRAINT fk_oi_product FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Seed products
INSERT INTO products (sku, product_name, category, quantity, reorder_level, unit_price, unit_cost) VALUES
('ELC-001','Wireless Mouse','Electronics',42,10,450,280),
('ELC-002','Mechanical Keyboard','Electronics',18,8,1200,800),
('ELC-003','USB-C Hub','Electronics',25,10,850,520),
('ELC-004','27" Monitor','Electronics',6,4,5200,3900),
('ELC-005','Noise-Cancel Headphones','Electronics',3,5,2600,1700),
('CLT-001','Cotton T-Shirt','Clothing',80,20,350,180),
('CLT-002','Hoodie','Clothing',35,15,750,420),
('CLT-003','Jeans','Clothing',28,12,900,520),
('ACC-001','Backpack','Accessories',22,8,800,470),
('ACC-002','Leather Wallet','Accessories',4,6,500,260),
('ACC-003','Watch','Accessories',12,5,2200,1400),
('HME-001','Desk Lamp','Home',16,8,550,300),
('HME-002','Coffee Maker','Home',9,4,2400,1600),
('HME-003','Air Fryer','Home',2,3,3300,2300);

INSERT INTO orders (customer_name, status, created_at) VALUES
('Ahmed Ibrahim','delivered','2025-09-14 10:22:00'),
('Sara Mahmoud','delivered','2025-10-02 14:05:00'),
('Omar Khaled','shipped','2025-10-21 09:40:00'),
('Mona Hassan','pending','2025-11-03 16:12:00'),
('Youssef Adel','pending','2025-11-08 11:30:00');

INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(1, 1, 2, 450),(1, 9, 1, 800),
(2, 2, 1, 1200),(2, 3, 1, 850),
(3, 4, 1, 5200),
(4, 6, 3, 350),(4, 8, 1, 900),
(5, 14, 1, 3300),(5, 13, 1, 2400);
