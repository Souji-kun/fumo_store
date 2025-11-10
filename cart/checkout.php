<?php
require_once '../includes/config.php';
include_once '../includes/header.php';

if (!isLoggedIn()) {
    header("Location: ../user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get cart items
$sql = "SELECT ci.*, p.name, p.price, p.stock 
        FROM cart_items ci 
        JOIN products p ON ci.product_id = p.id 
        WHERE ci.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    header("Location: index.php");
    exit();
}

// Calculate total
$total = 0;
$stock_error = false;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
    if ($item['quantity'] > $item['stock']) {
        $stock_error = true;
    }
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$stock_error) {
    try {
        $pdo->beginTransaction();

        // Create order
        $sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $total]);
        $order_id = $pdo->lastInsertId();

        // Add order items and update stock
        foreach ($cart_items as $item) {
            // Add order item
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);

            // Update stock
            $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }

        // Clear cart
        $sql = "DELETE FROM cart_items WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);

        $pdo->commit();
        
        // Redirect to success page
        header("Location: success.php?order_id=" . $order_id);
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error processing order: " . $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <h2>Checkout</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($stock_error): ?>
        <div class="alert alert-danger">
            Some items in your cart are no longer in stock in the requested quantity.
            Please return to your cart and adjust the quantities.
        </div>
        <a href="index.php" class="btn btn-primary">Return to Cart</a>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Payment Method</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="cod" name="payment_method" value="cod" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="cod">Cash on Delivery</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-block">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>