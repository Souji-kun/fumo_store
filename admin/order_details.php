<?php
require_once '../includes/config.php';
include_once '../includes/header.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    header("Location: orders.php");
    exit();
}

// Get order details
$sql = "SELECT o.*, u.username, u.email, u.first_name, u.last_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: orders.php");
    exit();
}

// Get order items
$sql = "SELECT oi.*, p.name, p.image_url 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Details</h2>
        <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Order #<?php echo $order_id; ?></h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <p><strong>Date:</strong> <?php echo date('M j, Y', strtotime($order['created_at'])); ?></p>
                            <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
                            <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
                        </div>
                    </div>

                    <h5>Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td>
                                            <?php if ($item['image_url']): ?>
                                                <img src="<?php echo '../' . $item['image_url']; ?>" alt="Product image" style="max-width: 50px;">
                                            <?php endif; ?>
                                        </td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Update Order Status</h4>
                </div>
                <div class="card-body">
                    <form action="update_status.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>
                                    Pending
                                </option>
                                <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>
                                    Processing
                                </option>
                                <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>
                                    Shipped
                                </option>
                                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>
                                    Delivered
                                </option>
                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>
                                    Cancelled
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>