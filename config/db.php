<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
define('BASE_URL', '/Project_CRUD_1');
$host = '127.0.0.1'; $user = 'root'; $pass = ''; $dbname = 'modern_shop_db';
try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("
            CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, role ENUM('admin', 'user') DEFAULT 'user', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
            CREATE TABLE categories (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL UNIQUE);
            CREATE TABLE products (id INT AUTO_INCREMENT PRIMARY KEY, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT, price DECIMAL(10,2) NOT NULL, stock INT NOT NULL DEFAULT 0, image VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE);
            CREATE TABLE orders (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, total_amount DECIMAL(10,2) NOT NULL, status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE);
            CREATE TABLE order_items (id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price DECIMAL(10,2) NOT NULL, FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE, FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE);
        ");
        $adminHash = password_hash('admin123', PASSWORD_DEFAULT);
        $userHash = password_hash('user123', PASSWORD_DEFAULT);
        $pdo->exec("
            INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@shop.com', '$adminHash', 'admin'), ('User', 'user@shop.com', '$userHash', 'user');
            INSERT INTO categories (name, slug) VALUES ('Electronics', 'electronics'), ('Fashion', 'fashion'), ('Home & Living', 'home-living');
            INSERT INTO products (category_id, name, description, price, stock, image) VALUES 
            (1, 'Modern Smartphone', 'High-end smartphone with an amazing camera.', 25990.00, 15, 'https://dummyimage.com/400x400/000/fff&text=Smartphone'),
            (1, 'Laptop Pro', 'Powerful laptop for professionals.', 49900.00, 8, 'https://dummyimage.com/400x400/000/fff&text=Laptop'),
            (2, 'Classic T-Shirt', 'Comfortable cotton t-shirt.', 390.00, 50, 'https://dummyimage.com/400x400/000/fff&text=T-Shirt'),
            (2, 'Running Shoes', 'Lightweight and durable.', 2500.00, 20, 'https://dummyimage.com/400x400/000/fff&text=Shoes'),
            (3, 'Coffee Maker', 'Start your day with perfect coffee.', 1200.00, 10, 'https://dummyimage.com/400x400/000/fff&text=Coffee+Maker'),
            (3, 'Desk Lamp', 'Adjustable LED desk lamp.', 450.00, 30, 'https://dummyimage.com/400x400/000/fff&text=Lamp');
        ");
    }
} catch (PDOException $e) { die("DB Error: " . $e->getMessage()); }
?>