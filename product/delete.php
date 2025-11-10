<?php
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

try {
    $id = $_GET['id'];
    
    // Get product info for image deletion
    $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    // Delete the product image if it exists
    if ($product['image_url'] && file_exists('../' . $product['image_url'])) {
        unlink('../' . $product['image_url']);
    }

    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Error deleting product: " . $e->getMessage());
}