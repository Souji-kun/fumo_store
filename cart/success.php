<?php
require_once '../includes/config.php';
include_once '../includes/header.php';

if (!isLoggedIn() || !isset($_GET['order_id'])) {
    header("Location: ../index.php");
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Get order details
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: ../index.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="jumbotron text-center">
        <h1 class="display-4 text-success"><i class="fa fa-check-circle"></i> Order Successful!</h1>
        <p class="lead">Thank you for your purchase. Your order has been placed successfully.</p>
        <hr class="my-4">
        <p>Order ID: #<?php echo $order_id; ?></p>
        <p>Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></p>
        <p>Order Status: <?php echo ucfirst($order['status']); ?></p>
        <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
        <a href="../user/orders.php" class="btn btn-secondary">View Orders</a>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>