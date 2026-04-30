<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $tbl_productsId = $_POST['product_id'];
    
    try {
        // Get product details
        $stmt = $pdo->prepare("SELECT * FROM tbl_products WHERE id = ?");
        $stmt->execute([$tbl_productsId]);
        $tbl_products = $stmt->fetch();
        
        if ($tbl_products) {
            // Initialize cart if not exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Add product to cart or increase quantity
            if (isset($_SESSION['cart'][$tbl_productsId])) {
                $_SESSION['cart'][$tbl_productsId]['quantity'] += 1;
            } else {
                $_SESSION['cart'][$tbl_productsId] = [
                    'id' => $tbl_products['id'],
                    'name' => $tbl_products['name'],
                    'price' => $tbl_products['price'],
                    'image' => $tbl_products['image_path'],
                    'quantity' => 1
                ];
            }
            
            // Redirect back to product page with success message
            $_SESSION['message'] = "Product added to cart!";
            header("Location: product.php?id=" . $tbl_productsId);
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// If no product ID or not POST request, redirect to homepage
header("Location: index.php");
exit();
?>