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
$quantity = $_POST['quantity'] ?? 1;

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

    // Update quantity
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $cart_item_id]);

    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Error updating cart: " . $e->getMessage());
}