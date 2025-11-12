<?php
/**
 * getProducts
 * Attempts to load products from the DB (config/database.php) if available.
 * If the DB is not configured or a query fails, returns a small set of
 * placeholder products so the UI still renders.
 *
 * @param string $category Category name (case-insensitive)
 * @param int $limit Max number of products to return
 * @return array
 */
function getProducts($category, $limit = 4) {
    $category = trim($category);

    // Try to load DB connection if available
    $dbLoaded = false;
    $products = [];

    // Database include path relative to this file (includes/)
    $dbPath = __DIR__ . '/../config/database.php';
    if (file_exists($dbPath)) {
        @include $dbPath; // expected to provide $conn (mysqli) or $pdo
        // Prefer mysqli-style $conn from older code
        if (isset($conn) && is_object($conn) && method_exists($conn, 'prepare')) {
            try {
                $sql = "SELECT * FROM products WHERE category = ? ORDER BY created_at DESC LIMIT ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("si", $category, $limit);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $products[] = $row;
                    }
                    $stmt->close();
                    $dbLoaded = true;
                }
            } catch (Exception $e) {
                // ignore and fall back to sample data
            }
        } elseif (isset($pdo) && is_object($pdo)) {
            // Support PDO if user creates $pdo in database.php
            try {
                $stmt = $pdo->prepare('SELECT * FROM products WHERE category = :cat ORDER BY created_at DESC LIMIT :limit');
                $stmt->bindValue(':cat', $category);
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $dbLoaded = true;
            } catch (Exception $e) {
                // ignore
            }
        }
    }

    // If DB not loaded or returned nothing, return sample products so UI isn't empty
    if (!$dbLoaded || empty($products)) {
        $sample = [
            ['id'=>101, 'name'=>'Classic Fumo Plush', 'price'=>25.00, 'image'=>'fumo-banner-right-image-01.jpg', 'category'=>'Plushies'],
            ['id'=>102, 'name'=>'Animal Costume Fumo', 'price'=>35.00, 'image'=>'fumo-banner-right-image-02.jpg', 'category'=>'Plushies'],
            ['id'=>103, 'name'=>'Fumo Collector Set', 'price'=>55.00, 'image'=>'fumo-banner-right-image-03.jpg', 'category'=>'Collectibles'],
            ['id'=>104, 'name'=>'Best Seller Fumo', 'price'=>45.00, 'image'=>'fumo-banner-right-image-04.jpg', 'category'=>'Best Sellers'],
            ['id'=>105, 'name'=>'Green Jacket Plushie', 'price'=>75.00, 'image'=>'men-01.jpg', 'category'=>'Women'],
            ['id'=>106, 'name'=>'Cute Cap', 'price'=>12.00, 'image'=>'kid-02.jpg', 'category'=>'Kid']
        ];

        // Filter sample by requested category (simple case-insensitive contains)
        $products = array_values(array_filter($sample, function($p) use ($category) {
            return stripos($p['category'], $category) !== false || stripos($category, $p['category']) !== false;
        }));

        // If no sample matched, just return first $limit items
        if (empty($products)) {
            $products = array_slice($sample, 0, $limit);
        } else {
            $products = array_slice($products, 0, $limit);
        }
    }

    return $products;
}

/**
 * displayProductCarousel
 * Build HTML for the product carousel items. Returns HTML string.
 */
function displayProductCarousel($products, $category = '') {
    $output = '';
    foreach ($products as $product) {
        $img = htmlspecialchars($product['image'] ?? 'placeholder.jpg', ENT_QUOTES);
        $name = htmlspecialchars($product['name'] ?? 'Unnamed', ENT_QUOTES);
        $price = isset($product['price']) ? number_format($product['price'], 2) : '0.00';
        $id = isset($product['id']) ? intval($product['id']) : 0;

        $output .= "\n        <div class=\"item\">\n            <div class=\"thumb\">\n                <div class=\"hover-content\">\n                    <ul>\n                        <li><a href=\"single-product.php?id={$id}\"><i class=\"fa fa-eye\"></i></a></li>\n                        <li><a href=\"single-product.php?id={$id}\"><i class=\"fa fa-star\"></i></a></li>\n                        <li><a href=\"single-product.php?id={$id}\"><i class=\"fa fa-shopping-cart\"></i></a></li>\n                    </ul>\n                </div>\n                <img src=\"assets/images/{$img}\" alt=\"{$name}\">\n            </div>\n            <div class=\"down-content\">\n                <h4>{$name}</h4>\n                <span>PHP{$price}</span>\n                <ul class=\"stars\">\n                    <li><i class=\"fa fa-star\"></i></li>\n                    <li><i class=\"fa fa-star\"></i></li>\n                    <li><i class=\"fa fa-star\"></i></li>\n                    <li><i class=\"fa fa-star\"></i></li>\n                    <li><i class=\"fa fa-star\"></i></li>\n                </ul>\n            </div>\n        </div>";
    }
    return $output;
}

?>