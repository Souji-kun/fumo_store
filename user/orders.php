<?php
require_once '../includes/config.php';
include_once '../includes/header.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user orders
$sql = "SELECT o.*, 
               COUNT(oi.id) as item_count,
               SUM(oi.quantity) as total_items
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>My Orders</h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">You haven't placed any orders yet.</div>
        <a href="../index.php" class="btn btn-primary">Start Shopping</a>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                            <td><?php echo $order['total_items']; ?> item(s)</td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo ucfirst($order['status']); ?></td>
                            <td>
                                <a href="order_details.php?id=<?php echo $order['id']; ?>" 
                                   class="btn btn-info btn-sm">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>