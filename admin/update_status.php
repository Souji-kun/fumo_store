<?php
require_once '../includes/config.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: orders.php");
    exit();
}

$order_id = $_POST['order_id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$order_id || !$status) {
    header("Location: orders.php");
    exit();
}

try {
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $order_id]);

    // If coming from order details page, redirect back there
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'order_details.php') !== false) {
        header("Location: order_details.php?id=" . $order_id);
    } else {
        header("Location: orders.php");
    }
    exit();
} catch (PDOException $e) {
    die("Error updating order status: " . $e->getMessage());
}