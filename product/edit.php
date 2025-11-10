<?php
require_once '../includes/config.php';
include_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category_id = $_POST['category_id'];
        
        // Handle file upload
        $image_url = $product['image_url']; // Keep existing image by default
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = '../uploads/products/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = uniqid() . '_' . $_FILES['image']['name'];
            $upload_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if it exists
                if ($product['image_url'] && file_exists('../' . $product['image_url'])) {
                    unlink('../' . $product['image_url']);
                }
                $image_url = 'uploads/products/' . $file_name;
            }
        }

        $sql = "UPDATE products 
                SET name = :name, description = :description, price = :price, 
                    stock = :stock, category_id = :category_id, image_url = :image_url 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'category_id' => $category_id,
            'image_url' => $image_url,
            'id' => $id
        ]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error updating product: " . $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <h2>Edit Product</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <?php if ($product['image_url']): ?>
                <div class="mb-2">
                    <img src="<?php echo '../' . $product['image_url']; ?>" alt="Current product image" style="max-width: 200px;">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
            <small class="form-text text-muted">Leave empty to keep current image</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>