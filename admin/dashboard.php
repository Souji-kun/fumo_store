?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Get counts for dashboard
$stats = [
    'users' => $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'],
    'products' => $conn->query("SELECT COUNT(*) as count FROM item")->fetch_assoc()['count'],
    'orders' => $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'],
    'pending_orders' => $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'")->fetch_assoc()['count']
];

// Get recent orders
$recentOrders = $conn->query("
    SELECT o.*, u.name as customer_name, p.payment_status, p.payment_method 
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    LEFT JOIN payments p ON o.order_id = p.order_id 
    ORDER BY o.created_at DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Get low stock products
$lowStockProducts = $conn->query("
    SELECT i.*, c.name as category_name 
    FROM item i 
    LEFT JOIN categories c ON i.category_id = c.category_id 
    WHERE i.quantity <= 5 
    ORDER BY i.quantity ASC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

include '../includes/adminHeader.php';
?>

<div class="container py-4">
    <h1 class="mb-4">Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Users</h6>
                            <h2 class="mb-0"><?= $stats['users'] ?></h2>
                        </div>
                        <i class="fas fa-users dashboard-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card dashboard-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Products</h6>
                            <h2 class="mb-0"><?= $stats['products'] ?></h2>
                        </div>
                        <i class="fas fa-box dashboard-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card dashboard-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Orders</h6>
                            <h2 class="mb-0"><?= $stats['orders'] ?></h2>
                        </div>
                        <i class="fas fa-shopping-cart dashboard-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card dashboard-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pending Orders</h6>
                            <h2 class="mb-0"><?= $stats['pending_orders'] ?></h2>
                        </div>
                        <i class="fas fa-clock dashboard-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="orders.php" class="btn btn-sm btn-chocolate">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><a href="orderDetails.php?id=<?= $order['order_id'] ?>">
                                            #<?= $order['order_id'] ?></a></td>
                                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                        <td><?= formatCurrency($order['total_amount']) ?></td>
                                        <td><span class="badge status-<?= $order['order_status'] ?>">
                                            <?= ucfirst($order['order_status']) ?></span></td>
                                        <td><span class="badge bg-<?= $order['payment_status'] === 'completed' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($order['payment_status']) ?></span></td>
                                        <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Products -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Low Stock Alert</h5>
                    <a href="inventory.php" class="btn btn-sm btn-chocolate">View All</a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($lowStockProducts as $product): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                                    <small class="text-danger"><?= $product['quantity'] ?> left</small>
                                </div>
                                <p class="mb-1">Category: <?= htmlspecialchars($product['category_name']) ?></p>
                                <small>Price: <?= formatCurrency($product['sell_price']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>