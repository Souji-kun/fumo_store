<?php
require_once '../includes/config.php';
include_once '../includes/header.php';

if (!isLoggedIn()) {
    header("Location: ../user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get cart items
$sql = "SELECT ci.*, p.name, p.price, p.image_url 
        FROM cart_items ci 
        JOIN products p ON ci.product_id = p.id 
        WHERE ci.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<div class="container mt-5">
    <h2>Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Your cart is empty.</div>
        <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>
                                <?php if ($item['image_url']): ?>
                                    <img src="<?php echo '../' . $item['image_url']; ?>" alt="Product image" style="max-width: 50px;">
                                <?php endif; ?>
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form action="update.php" method="POST" class="d-inline">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                           min="1" max="99" class="form-control d-inline-block" style="width: 80px;"
                                           onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <form action="remove.php" method="POST" class="d-inline">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <a href="../index.php" class="btn btn-secondary">Continue Shopping</a>
            </div>
            <div class="col-md-6 text-right">
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>