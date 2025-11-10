<?php
require_once '../includes/config.php';

if (!isLoggedIn()) {
    header("Location: ../user/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_item_id = $_POST['cart_item_id'] ?? null;

if (!$cart_item_id) {
    header("Location: index.php");
    exit();
}

try {
    // Verify cart item belongs to user
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_item_id, $user_id]);
    if (!$stmt->fetch()) {
        header("Location: index.php");
        exit();
    }

    // Delete the cart item
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ?");
    $stmt->execute([$cart_item_id]);

    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Error removing item from cart: " . $e->getMessage());
}