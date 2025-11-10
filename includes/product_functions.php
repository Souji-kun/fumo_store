<?php
require_once __DIR__ . '/config.php';

function getProducts($category = null, $limit = 4, $offset = 0) {
    global $pdo;
    
    $params = [];
    $where = '';
    
    if ($category) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE c.name = :category 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        $params = [
            ':category' => $category,
            ':limit' => $limit,
            ':offset' => $offset
        ];
    } else {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        $params = [
            ':limit' => $limit,
            ':offset' => $offset
        ];
    }

    try {
        $stmt = $pdo->prepare($sql);
        
        // PDO::PARAM_INT needs to be explicitly set for LIMIT and OFFSET
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        if ($category) {
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getProducts: " . $e->getMessage());
        return [];
    }
}

function getProductsByIds($ids) {
    global $pdo;
    
    if (empty($ids)) {
        return [];
    }

    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.id IN ($placeholders)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getProductsByIds: " . $e->getMessage());
        return [];
    }
}

function displayProductCarousel($products) {
    $output = '';
    foreach ($products as $product) {
        $output .= '
        <div class="item">
            <div class="thumb">
                <div class="hover-content">
                    <ul>
                        <li><a href="single-product.php?id=' . $product['id'] . '"><i class="fa fa-eye"></i></a></li>
                        <li><a href="#" class="add-to-wishlist" data-product-id="' . $product['id'] . '"><i class="fa fa-star"></i></a></li>
                        <li><a href="#" class="add-to-cart" data-product-id="' . $product['id'] . '"><i class="fa fa-shopping-cart"></i></a></li>
                    </ul>
                </div>
                <img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '">
            </div>
            <div class="down-content">
                <h4>' . htmlspecialchars($product['name']) . '</h4>
                <span>$' . number_format($product['price'], 2) . '</span>
                <ul class="stars">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
        </div>';
    }
    return $output;
}

function getProductStock($product_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error in getProductStock: " . $e->getMessage());
        return 0;
    }
}
?>