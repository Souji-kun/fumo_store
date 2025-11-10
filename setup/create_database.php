<?php
$host = "localhost";
$username = "root";
$password = "";

// Create connection to MySQL server
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS fumo_store";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Select the database
$conn->select_db("fumo_store");

// Create products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Products table created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Sample product data
$sampleProducts = [
    [
        'name' => 'Fumo Reimu',
        'description' => 'High-quality Reimu plush toy',
        'price' => 1499.99,
        'image' => 'plushie-02.jpg',
        'category' => 'Plushies',
        'stock' => 10
    ],
    [
        'name' => 'Fumo Marisa',
        'description' => 'Adorable Marisa plush toy',
        'price' => 1499.99,
        'image' => 'plushie-04.jpg',
        'category' => 'Plushies',
        'stock' => 8
    ],
    [
        'name' => 'Fumo Cirno',
        'description' => 'Cute Cirno plush toy',
        'price' => 1299.99,
        'image' => 'plushie-03.jpg',
        'category' => 'Plushies',
        'stock' => 15
    ],
    [
        'name' => 'Fumo Sakuya',
        'description' => 'Limited edition Sakuya plush toy',
        'price' => 1999.99,
        'image' => 'plushie-01.jpg',
        'category' => 'Plushies',
        'stock' => 5
    ]
];

// Clear existing products
$conn->query("TRUNCATE TABLE products");

// Insert sample products
$stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category, stock) VALUES (?, ?, ?, ?, ?, ?)");

foreach ($sampleProducts as $product) {
    $stmt->bind_param("ssdssi", 
        $product['name'],
        $product['description'],
        $product['price'],
        $product['image'],
        $product['category'],
        $product['stock']
    );
    
    if ($stmt->execute()) {
        echo "Product {$product['name']} added successfully\n";
    } else {
        echo "Error adding product {$product['name']}: " . $stmt->error . "\n";
    }
}

$stmt->close();
$conn->close();
?>