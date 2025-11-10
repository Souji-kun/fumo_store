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

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'assets/images/default-profile.png',
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully\n";
} else {
    echo "Error creating users table: " . $conn->error . "\n";
}

// Create a default admin user (password: admin123)
$adminUser = [
    'username' => 'admin',
    'email' => 'admin@fumostore.com',
    'password' => password_hash('admin123', PASSWORD_DEFAULT),
    'role' => 'admin'
];

// Check if admin exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $adminUser['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insert admin user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", 
        $adminUser['username'],
        $adminUser['email'],
        $adminUser['password'],
        $adminUser['role']
    );
    
    if ($stmt->execute()) {
        echo "Admin user created successfully\n";
    } else {
        echo "Error creating admin user: " . $stmt->error . "\n";
    }
} else {
    echo "Admin user already exists\n";
}

$stmt->close();
$conn->close();
?>