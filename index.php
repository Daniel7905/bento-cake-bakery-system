<?php
// Database connection
require_once 'connection.php';

// Function to fetch categories
function getCategories() {
    global $conn;
    
    $sql = "SELECT * FROM tbl_category WHERE is_active = 1 ORDER BY category";
    $result = $conn->query($sql);
    
    $categories = [];
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

// Function to fetch products by category
function getProductsByCategory($category_id, $limit = null) {
    global $conn;
    
    $sql = "SELECT p.*, c.category 
            FROM tbl_products p
            LEFT JOIN tbl_category c ON p.category_id = c.category_id
            WHERE p.category_id = ? AND p.status = 'available'";
    
    if ($limit) {
        $sql .= " LIMIT ?";
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($limit) {
        $stmt->bind_param("ii", $category_id, $limit);
    } else {
        $stmt->bind_param("i", $category_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get product image - FIXED VERSION
function getProductImage($product_id) {
    global $conn;
    
    // First check if the product has a main image in tbl_products
    $sql_main = "SELECT image FROM tbl_products WHERE productmaster_id = ?";
    $stmt_main = $conn->prepare($sql_main);
    $stmt_main->bind_param("i", $product_id);
    $stmt_main->execute();
    $result_main = $stmt_main->get_result();
    
    if ($result_main->num_rows > 0) {
        $row = $result_main->fetch_assoc();
        if (!empty($row['image'])) {
            $image_path = $row['image'];
            
            // Check if the image file exists
            if (file_exists($image_path)) {
                return $image_path;
            }
            
            // Check if it's a relative path from root
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path)) {
                return $image_path;
            }
            
            // Check if it's just a filename in the uploads directory
            if (file_exists('uploads/products/' . $image_path)) {
                return 'uploads/products/' . $image_path;
            }
        }
    }
    
    // If no main image, check tbl_product_images
    $sql = "SELECT image_path FROM tbl_product_images WHERE product_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $image_path = $result->fetch_assoc()['image_path'];
        
        // Check if the image file actually exists
        if (file_exists($image_path)) {
            return $image_path;
        }
        
        // Check if it's a relative path from root
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path)) {
            return $image_path;
        }
        
        // Check if it's just a filename in the uploads directory
        if (file_exists('uploads/products/' . $image_path)) {
            return 'uploads/products/' . $image_path;
        }
    }
    
    // Return default image
    return 'default-cake.jpg'; // Make sure this file exists in your root directory
}

// Fetch categories
$categories = getCategories();

// Fetch products for each category (limit to 6 products per category)
$categoryProducts = [];
foreach ($categories as $category) {
    $categoryProducts[$category['category_id']] = getProductsByCategory($category['category_id'], 6);
}
// Handle flavor selection
$selected_flavors = isset($_POST['flavors']) ? $_POST['flavors'] : array();

$insert_query = "INSERT INTO tbl_products 
                (product_name, description, category_id, price, status, image) 
                VALUES 
                ('$product_name', '$description', '$category_id','$price', '$status', '$image')";

if(mysqli_query($conn, $insert_query)) {
    $product_id = mysqli_insert_id($conn);
    
    // Insert selected flavors into product_flavors table (assuming you have this table)
    if(!empty($selected_flavors)) {
        foreach($selected_flavors as $flavor_id) {
            $flavor_id = mysqli_real_escape_string($conn, $flavor_id);
            $flavor_query = "INSERT INTO product_flavors (product_id, flavor_id) VALUES ('$product_id', '$flavor_id')";
            mysqli_query($conn, $flavor_query);
        }
    }
    
    header("Location: admin_home.php?message=Product+added+successfully&type=success");
} else {
    header("Location: admin_home.php?message=Error+adding+product: ".mysqli_error($conn)."&type=error");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bento Cake | Food Delivery Hub</title>
    <style>
        /* Your existing CSS styles */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25D366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.5);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s;
            animation: pulse 2s infinite;
        }
        
        .whatsapp-float:hover {
            background-color: #128C7E;
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 25px rgba(18, 140, 126, 0.7);
        }
        
        .whatsapp-float:active {
            transform: scale(0.95);
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }
        
        @media screen and (max-width: 768px) {
            .whatsapp-float {
                width: 50px;
                height: 50px;
                bottom: 20px;
                right: 20px;
                font-size: 24px;
            }
        }
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
        }
        .product-box:hover {
            transform: translateY(-5px);
        }
        .product-caption {
            padding: 15px;
        }
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
        }
        .view-all-btn {
            margin-top: 20px;
            text-align: center;
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
        .section-header-left {
            margin-bottom: 30px;
        }
        
        .section-header-left .title {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .section-header-left .title {
                font-size: 1.8rem;
            }
        /* Header Styles */
        header {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(90, 13, 36, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        /* Debug styles - can be removed after fixing */
        .image-debug {
            border: 2px solid red;
            position: relative;
        }
        .image-debug::after {
            content: "Image not found";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,0,0,0.7);
            color: white;
            padding: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php include('headers/mainnav.php'); ?>
    <a href="https://wa.me/+919353383242" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    <!-- slider -->
    <section class="about-us-slider swiper-container p-relative">
        <div class="swiper-wrapper">
            <?php
            // Fetch active banners ordered by display order
            $banner_query = "SELECT * FROM tbl_banners WHERE is_active = 1 ORDER BY display_order, banner_id";
            $banner_result = mysqli_query($conn, $banner_query);
            
            while($banner = mysqli_fetch_assoc($banner_result)):
            ?>
            <div class="swiper-slide slide-item">
                <img src="<?php echo $banner['image_path']; ?>" class="img-fluid full-width" alt="Banner">
                <div class="transform-center">
                    <div class="container">
                        <div class="row justify-content-<?php echo $banner['alignment']; ?>">
                            <div class="col-lg-7 align-self-center">
                                <div class="right-side-content text-<?php echo $banner['alignment']; ?>">
                                    <h1 class="text-custom-white fw-600"><?php echo htmlspecialchars($banner['title']); ?></h1>
                                    <?php if(!empty($banner['subtitle'])): ?>
                                    <h3 class="text-custom-white fw-400"><?php echo htmlspecialchars($banner['subtitle']); ?></h3>
                                    <?php endif; ?>
                                    <?php if(!empty($banner['button_text']) && !empty($banner['button_link'])): ?>
                                    <a href="<?php echo $banner['button_link']; ?>" class="btn-second btn-submit"><?php echo htmlspecialchars($banner['button_text']); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay overlay-bg"></div>
            </div>
            <?php endwhile; ?>
        </div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </section>
    <!-- slider -->
    
    <!-- Browse by category -->
    <section class="browse-cat u-line section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header-left">
                        <h3 class="text-light-black header-title title">Our Categories</h3>
                        <p class="text-light-white">Explore our delicious cake categories</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="category-slider swiper-container">
                        <div class="swiper-wrapper">
                            <?php foreach ($categories as $category): ?>
                            <div class="swiper-slide">
                                <a href="order.php?category_id=<?= $category['category_id'] ?>" class="categories">
                                    <div class="category-icon bg-light-green"> 
                                        <i class="fas fa-birthday-cake"></i>
                                    </div> 
                                    <span class="text-light-black cat-name"><?= htmlspecialchars($category['category']) ?></span>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Browse by category -->
    
    <!-- Display products for each category -->
    <?php foreach ($categories as $category): 
        $products = $categoryProducts[$category['category_id']];
        if (!empty($products)):
    ?>
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header-left">
                        <h3 class="text-light-black header-title title"><?= htmlspecialchars($category['category']) ?></h3>
                        <p class="text-light-white"><?= htmlspecialchars($category['description'] ?? 'Delicious cakes for every occasion') ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($products as $product): 
                    $image_path = getProductImage($product['productmaster_id']);
                    $image_exists = file_exists($image_path) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path);
                ?>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product-box mb-xl-20">
                        <div class="product-img">
                            <a href="product.php?id=<?= $product['productmaster_id'] ?>">
                                <img src="<?= $image_path ?>" 
                                     class="img-fluid full-width <?= !$image_exists ? 'image-debug' : '' ?>" 
                                     alt="<?= htmlspecialchars($product['product_name']) ?>" 
                                     style="height: 250px; object-fit: cover;"
                                     onerror="this.src='default-cake.jpg'; this.classList.add('image-debug')">
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
            </div>
            <div class="view-all-btn">
                <a href="order.php?category_id=<?= $category['category_id'] ?>" class="btn btn-first">View All <?= htmlspecialchars($category['category']) ?></a>
            </div>
        </div>
    </section>
    <?php else: ?>
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header-left">
                        <h3 class="text-light-black header-title title"><?= htmlspecialchars($category['category']) ?></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-birthday-cake"></i>
                        <h4>No products available yet</h4>
                        <p>Check back soon for delicious <?= htmlspecialchars($category['category']) ?>!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; endforeach; ?>

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
    
    <!-- Add Swiper JS for category slider -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize category slider
            var categorySwiper = new Swiper('.category-slider', {
                slidesPerView: 3,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.category-slider .swiper-button-next',
                    prevEl: '.category-slider .swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 3,
                        spaceBetween: 15
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 20
                    },
                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 25
                    }
                }
            });
            
            // Initialize banner slider
            var bannerSwiper = new Swiper('.about-us-slider', {
                loop: true,
                navigation: {
                    nextEl: '.about-us-slider .swiper-button-next',
                    prevEl: '.about-us-slider .swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                },
            });
        });
    </script>
</body>
</html>