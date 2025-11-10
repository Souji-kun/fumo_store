<?php
session_start();

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'fumo_store');


// Attempt to connect to MySQL database
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Site configuration
define('SITE_URL', 'http://localhost/fumo_store2/');
define('ADMIN_EMAIL', 'admin@fumostore.com');

// Upload configuration
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5000000); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Shopping cart configuration
define('CART_COOKIE_NAME', 'fumo_cart');
define('CART_COOKIE_EXPIRE', time() + (86400 * 30)); // 30 days

// User roles
define('ROLE_ADMIN', 1);
define('ROLE_USER', 2);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);