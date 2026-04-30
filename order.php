<?php
// Database connection
require_once 'connection.php';

// Function to get category details
function getCategory($category_id) {
    global $conn;
    
    $sql = "SELECT * FROM tbl_category WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// Function to get products by category
function getProductsByCategory($category_id) {
    global $conn;
    
    $sql = "SELECT p.*, c.category 
            FROM tbl_products p
            LEFT JOIN tbl_category c ON p.category_id = c.category_id
            WHERE p.category_id = ? AND p.status = 'available'
            ORDER BY p.product_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

// Function to get product image
function getProductImage($product_id) {
    global $conn;
    
    $sql = "SELECT image_path FROM tbl_product_images WHERE product_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['image_path'];
    }
    
    return 'default-cake.jpg'; // Default image
}

// Get category ID from URL parameter
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Get category details
$category = getCategory($category_id);

// If category doesn't exist, redirect to homepage
if (!$category) {
    header("Location: index.php");
    exit();
}

// Get products for this category
$products = getProductsByCategory($category_id);

// Get all categories for sidebar
function getAllCategories() {
    global $conn;
    
    $sql = "SELECT * FROM tbl_category WHERE is_active = 1 ORDER BY category";
    $result = $conn->query($sql);
    
    $categories = [];
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

$categories = getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category['category']) ?> | Bento Cake</title>
    <style>
        header{
            background-color: #fcc4d4;
        }
        body {
            background-color: #fcc4d4;
            color: #5a0d24;
        }
        .text-light-black, .text-light-white, .text-custom-white {
            color: #5a0d24 !important;
        }
        .bg-light-green, .bg-gradient-red, .bg-yellow, .bg-red, .bg-green, .bg-gradient-green, .bg-gradient-orange {
            background-color: #5a0d24 !important;
            color: #fcc4d4 !important;
        }
        .overlay-bg {
            background: rgba(90, 13, 36, 0.5) !important;
        }
        .btn-second, .btn-submit, .btn-first {
            background-color: #5a0d24 !important;
            color: #fcc4d4 !important;
            border-color: #5a0d24 !important;
        }
        .btn-second:hover, .btn-submit:hover, .btn-first:hover {
            background-color: #3a0818 !important;
            color: #fcc4d4 !important;
        }
        .section-padding {
            padding: 60px 0;
            background-color: #fcc4d4;
        }
        .u-line {
            border-bottom: 1px solid #5a0d24;
        }
        .footer-top {
            background-color: #5a0d24 !important;
        }
        .footer-top .text-custom-white {
            color: #fcc4d4 !important;
        }
        .footer-top .text-light-green {
            color: #fcc4d4 !important;
        }
        .swiper-button-next, .swiper-button-prev {
            color: #5a0d24 !important;
        }
        .product-box {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(90, 13, 36, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }
        .product-box:hover {
            transform: translateY(-5px);
        }
        .product-caption {
            padding: 15px;
        }
        .category-sidebar {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .category-list {
            list-style: none;
            padding: 0;
        }
        .category-list li {
            margin-bottom: 10px;
        }
        .category-list a {
            color: #5a0d24;
            text-decoration: none;
            display: block;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .category-list a:hover, .category-list a.active {
            background-color: #5a0d24;
            color: #fcc4d4;
        }
        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: #5a0d24;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.7;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        .breadcrumb-item a {
            color: #5a0d24;
            text-decoration: none;
        }
        .breadcrumb-item.active {
            color: #3a0818;
        }
    </style>
</head>
<body>
    <?php include('headers/mainnav.php'); ?>
    
    <!-- Breadcrumb -->
    <section class="section-padding" style="padding-top: 30px; padding-bottom: 20px;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($category['category']) ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Category Products -->
    <section class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Category Sidebar -->
                    <div class="category-sidebar">
                        <h4 class="text-light-black mb-4">Categories</h4>
                        <ul class="category-list">
                            <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="order.php?category_id=<?= $cat['category_id'] ?>" 
                                   class="<?= $cat['category_id'] == $category_id ? 'active' : '' ?>">
                                    <i class="fas fa-chevron-right me-2"></i>
                                    <?= htmlspecialchars($cat['category']) ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-header-left">
                                <h2 class="text-light-black header-title title"><?= htmlspecialchars($category['category']) ?></h2>
                                <?php if (!empty($category['description'])): ?>
                                <p class="text-light-white"><?= htmlspecialchars($category['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product-box">
                                    <div class="product-img">
                                        <a href="product.php?id=<?= $product['productmaster_id'] ?>">
                                            <img src="<?= getProductImage($product['productmaster_id']) ?>" 
                                                 class="img-fluid full-width" 
                                                 alt="<?= htmlspecialchars($product['product_name']) ?>" 
                                                 style="height: 250px; object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="product-caption">
                                        <div class="title-box">
                                            <h6 class="product-title">
                                                <a href="product.php?id=<?= $product['productmaster_id'] ?>" class="text-light-black">
                                                    <?= htmlspecialchars($product['product_name']) ?>
                                                </a>
                                            </h6>
                                        </div>
                                        <p class="text-light-white"><?= htmlspecialchars(substr($product['description'], 0, 100)) . (strlen($product['description']) > 100 ? '...' : '') ?></p>
                                        <div class="product-details">
                                            <div class="price-time">
                                                <span class="text-light-white price">₹<?= number_format($product['price'], 2) ?></span>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a href="product.php?id=<?= $product['productmaster_id'] ?>" class="btn btn-first btn-sm">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="fas fa-birthday-cake"></i>
                                    <h4>No products available in this category</h4>
                                    <p>Check back soon for delicious <?= htmlspecialchars($category['category']) ?>!</p>
                                    <a href="index.php" class="btn btn-first mt-3">Back to Home</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <div class="footer-top section-padding bg-black">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 col-sm-4 col-6 mb-sm-20">
                    <div class="icon-box"> <span class="text-light-green"><i class="flaticon-credit-card-1"></i></span>
                        <span class="text-custom-white">100% Payment<br>Secured</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-sm-20">
                    <div class="icon-box"> <span class="text-light-green"><i class="flaticon-wallet-1"></i></span>
                        <span class="text-custom-white">Support lots<br> of Payments</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-sm-20">
                    <div class="icon-box"> <span class="text-light-green"><i class="flaticon-help"></i></span>
                        <span class="text-custom-white">24 hours / 7 days<br>Support</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="icon-box"> <span class="text-light-green"><i class="flaticon-truck"></i></span>
                        <span class="text-custom-white">Free Delivery<br>with $50</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="icon-box"> <span class="text-light-green"><i class="flaticon-guarantee"></i></span>
                        <span class="text-custom-white">Best Price<br>Guaranteed</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="icon-box"> <span class="text-light-green"><i class="flaticon-app-file-symbol"></i></span>
                        <span class="text-custom-white">Mobile Apps<br>Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('headers/footer.php'); ?>
</body>
</html>