<?php
include("connection.php");

// Handle order status update
if(isset($_POST['update_order_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $update_query = "UPDATE tbl_orders SET status = '$status' WHERE order_id = '$order_id'";
    if(mysqli_query($conn, $update_query)) {
        header("Location: admin_home.php?message=Order+status+updated+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+updating+order+status&type=error");
    }
    exit();
}

// Handle order deletion
if(isset($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // First delete order items
        $delete_items = "DELETE FROM tbl_order_items WHERE order_id = '$order_id'";
        mysqli_query($conn, $delete_items);
        
        // Then delete the order
        $delete_order = "DELETE FROM tbl_orders WHERE order_id = '$order_id'";
        mysqli_query($conn, $delete_order);
        
        // Commit transaction
        mysqli_commit($conn);
        
        header("Location: admin_home.php?message=Order+deleted+successfully&type=success");
    } catch (Exception $e) {
        // Rollback transaction if any query fails
        mysqli_rollback($conn);
        header("Location: admin_home.php?message=Error+deleting+order&type=error");
    }
    
    exit();
}

// Fetch all orders with customer details
$orders_query = "SELECT o.*, c.name as customer_name, c.phone, l.email 
                 FROM tbl_orders o 
                 JOIN tbl_customer c ON o.customer_id = c.customer_id 
                 JOIN tbl_login l ON c.login_id = l.login_id 
                 ORDER BY o.order_date DESC";
$orders_result = mysqli_query($conn, $orders_query);

// Fetch all categories from the database
$category_query = "SELECT * FROM tbl_category";
$category_result = mysqli_query($conn, $category_query);

// Fetch all products with category and subcategory names
$product_query = "SELECT p.*, c.category 
                  FROM tbl_products p
                  JOIN tbl_category c ON p.category_id = c.category_id";
$product_result = mysqli_query($conn, $product_query);

// Handle flavor form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_flavor'])) {
    $flavor_name = mysqli_real_escape_string($conn, $_POST['flavor_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $is_active = mysqli_real_escape_string($conn, $_POST['is_active']);
    
    if(isset($_POST['flavor_id']) && !empty($_POST['flavor_id'])) {
        // Update existing flavor
        $flavor_id = mysqli_real_escape_string($conn, $_POST['flavor_id']);
        $update_query = "UPDATE tbl_flavors SET 
                        flavor_name = '$flavor_name',
                        description = '$description',
                        is_active = '$is_active'
                        WHERE flavor_id = '$flavor_id'";
        
        if(mysqli_query($conn, $update_query)) {
            header("Location: admin_home.php?message=Flavor+updated+successfully&type=success");
        } else {
            header("Location: admin_home.php?message=Error+updating+flavor: ".mysqli_error($conn)."&type=error");
        }
    } else {
        // Add new flavor
        $insert_query = "INSERT INTO tbl_flavors 
                        (flavor_name, description, is_active) 
                        VALUES 
                        ('$flavor_name', '$description', '$is_active')";
        
        if(mysqli_query($conn, $insert_query)) {
            header("Location: admin_home.php?message=Flavor+added+successfully&type=success");
        } else {
            header("Location: admin_home.php?message=Error+adding+flavor: ".mysqli_error($conn)."&type=error");
        }
    }
    exit();
}

// Handle flavor deletion
if(isset($_GET['delete_flavor'])) {
    $flavor_id = mysqli_real_escape_string($conn, $_GET['delete_flavor']);
    $delete_query = "DELETE FROM tbl_flavors WHERE flavor_id = '$flavor_id'";
    if(mysqli_query($conn, $delete_query)) {
        header("Location: admin_home.php?message=Flavor+deleted+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+deleting+flavor: ".mysqli_error($conn)."&type=error");
    }
    exit();
}

// Fetch all customers from the database
$customer_query = "SELECT c.*, l.email, l.status as login_status 
                   FROM tbl_customer c
                   JOIN tbl_login l ON c.login_id = l.login_id";
$customer_result = mysqli_query($conn, $customer_query);

// Handle review deletion
if(isset($_GET['delete_review'])) {
    $review_id = $_GET['delete_review'];
    $delete_query = "DELETE FROM tbl_reviews WHERE review_id = '$review_id'";
    if(mysqli_query($conn, $delete_query)) {
        header("Location: admin_home.php?message=Review+deleted+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+deleting+review&type=error");
    }
    exit();
}

// Handle review form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $review_id = $_POST['review_id'] ?? null;
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    
    if($review_id) {
        // Update existing review
        $update_query = "UPDATE tbl_reviews SET 
                        customer_id = '$customer_id',
                        product_id = '$product_id',
                        review_text = '$review_text',
                        rating = '$rating'
                        WHERE review_id = '$review_id'";
        
        if(mysqli_query($conn, $update_query)) {
            header("Location: admin_home.php?message=Review+updated+successfully&type=success");
        } else {
            header("Location: admin_home.php?message=Error+updating+review&type=error");
        }
    } else {
        // Add new review
        $insert_query = "INSERT INTO tbl_reviews 
                        (customer_id, product_id, review_text, rating) 
                        VALUES 
                        ('$customer_id', '$product_id', '$review_text', '$rating')";
        
        if(mysqli_query($conn, $insert_query)) {
            header("Location: admin_home.php?message=Review+added+successfully&type=success");
        } else {
            header("Location: admin_home.php?message=Error+adding+review&type=error");
        }
    }
    exit();
}

// Handle product deletion
if(isset($_GET['delete_product'])) {
    $product_id = $_GET['delete_product'];
    $delete_query = "DELETE FROM tbl_products WHERE productmaster_id = '$product_id'";
    if(mysqli_query($conn, $delete_query)) {
        header("Location: admin_home.php?message=Product+deleted+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+deleting+product&type=error");
    }
    exit();
}

// Handle customer deletion
if(isset($_GET['delete_customer'])) {
    $customer_id = $_GET['delete_customer'];
    
    // First get the login_id to delete from tbl_login
    $get_login_query = "SELECT login_id FROM tbl_customer WHERE customer_id = '$customer_id'";
    $login_result = mysqli_query($conn, $get_login_query);
    $login_row = mysqli_fetch_assoc($login_result);
    $login_id = $login_row['login_id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Delete from tbl_customer
        $delete_customer = "DELETE FROM tbl_customer WHERE customer_id = '$customer_id'";
        mysqli_query($conn, $delete_customer);
        
        // Delete from tbl_login
        $delete_login = "DELETE FROM tbl_login WHERE login_id = '$login_id'";
        mysqli_query($conn, $delete_login);
        
        // Commit transaction
        mysqli_commit($conn);
        
        header("Location: admin_home.php?message=Customer+deleted+successfully&type=success");
    } catch (Exception $e) {
        // Rollback transaction if any query fails
        mysqli_rollback($conn);
        header("Location: admin_home.php?message=Error+deleting+customer&type=error");
    }
    
    exit();
}

// Handle customer status update
if(isset($_POST['update_customer_status'])) {
    $login_id = $_POST['login_id'];
    $status = $_POST['status'];
    
    $update_query = "UPDATE tbl_login SET status = '$status' WHERE login_id = '$login_id'";
    if(mysqli_query($conn, $update_query)) {
        header("Location: admin_home.php?message=Customer+status+updated+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+updating+customer+status&type=error");
    }
    exit();
}
    
    // Handle product form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Handle image upload
    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/products/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Generate unique filename
            $unique_filename = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $unique_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            } else {
                header("Location: admin_home.php?message=Error+uploading+image&type=error");
                exit();
            }
        } else {
            header("Location: admin_home.php?message=File+is+not+an+image&type=error");
            exit();
        }
    } else if(isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
        // Error other than "no file selected"
        header("Location: admin_home.php?message=Error+uploading+image:+".$_FILES['image']['error']."&type=error");
        exit();
    }
    
    if(isset($_POST['product_id'])) {
        // Update existing product
        $product_id = $_POST['product_id'];
        if(!empty($image)) {
            $update_query = "UPDATE tbl_products SET 
                            product_name = '$product_name',
                            description = '$description',
                            category_id = '$category_id',
                            price = '$price',
                            status = '$status',
                            image = '$image'
                            WHERE productmaster_id = '$product_id'";
        } else {
            $update_query = "UPDATE tbl_products SET 
                            product_name = '$product_name',
                            description = '$description',
                            category_id = '$category_id',
                            price = '$price',
                            status = '$status'
                            WHERE productmaster_id = '$product_id'";
        }
        
        if(mysqli_query($conn, $update_query)) {
            header("Location: admin_home.php?message=Product+updated+successfully&type=success");
        } else {
            header("Location: admin_home.php?message=Error+updating+product: ".mysqli_error($conn)."&type=error");
        }
    } else {
        // Add new product - image is required for new products
        if(empty($image)) {
            header("Location: admin_home.php?message=Image+is+required+for+new+products&type=error");
            exit();
        }
        
        $insert_query = "INSERT INTO tbl_products 
                        (product_name, description, category_id, price, status, image) 
                        VALUES 
                        ('$product_name', '$description', '$category_id','$price', '$status', '$image')";
        
        if(mysqli_query($conn, $insert_query)) {
            header("Location: admin_home.php?message=Product+added+successfully&type=success");
        } else {
            header("Location: admin_home.php?message=Error+adding+product: ".mysqli_error($conn)."&type=error");
        }
    }
    if(mysqli_query($conn, $update_query)) {
        // Handle flavor updates
        $selected_flavors = isset($_POST['flavors']) ? $_POST['flavors'] : array();
        
        // Delete existing flavor associations
        $delete_flavors_query = "DELETE FROM product_flavors WHERE product_id = '$product_id'";
        mysqli_query($conn, $delete_flavors_query);
        
        // Insert new flavor associations
        if(!empty($selected_flavors)) {
            foreach($selected_flavors as $flavor_id) {
                $flavor_id = mysqli_real_escape_string($conn, $flavor_id);
                $flavor_query = "INSERT INTO product_flavors (product_id, flavor_id) VALUES ('$product_id', '$flavor_id')";
                mysqli_query($conn, $flavor_query);
            }
        }
        
        header("Location: admin_home.php?message=Product+updated+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+updating+product: ".mysqli_error($conn)."&type=error");
    }
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munchbox | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .flavors-container {
    background-color: #f8f9fa;
}

.flavors-container .form-check {
    margin-bottom: 5px;
}

.flavors-container .form-check-input {
    margin-right: 8px;
}
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 25px;
            margin-top: 20px;
        }
        .page-title {
            color: #6c757d;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .order-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s;
        }
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8em;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .table th {
            background-color: #f1f5f9;
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .order-detail-label {
            font-weight: 600;
            color: #495057;
        }
        /* Base styles */
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --primary-color: #28a745;
            --secondary-color: #6c757d;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #343a40;
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .sidebar-heading {
            display: none;
        }
        
        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 0.75rem 0.5rem;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-left: 3px solid transparent;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--primary-color);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .topbar {
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 999;
            transition: all 0.3s ease;
        }
        
        .topbar.expanded {
            left: var(--sidebar-collapsed-width);
        }
        
        /* Toggle button for sidebar */
        .sidebar-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            margin-right: 15px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-show {
                transform: translateX(0);
                width: var(--sidebar-width);
            }
            
            .sidebar.mobile-show .nav-link span,
            .sidebar.mobile-show .sidebar-brand span,
            .sidebar.mobile-show .sidebar-heading {
                display: inline;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .topbar {
                left: 0;
            }
            
            .main-content.expanded, .topbar.expanded {
                margin-left: 0;
                left: 0;
            }
        }
        
        /* Card and table styles */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        /* Order details styles */
        .order-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .status-badge {
            font-size: 0.85em;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        /* Image styles */
        .img-thumbnail {
            max-width: 50px;
            max-height: 50px;
        }
        
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
        
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .card-header .btn {
                margin-top: 10px;
                width: 100%;
            }
            
            .table th, .table td {
                white-space: nowrap;
            }
            
            .modal-dialog {
                margin: 1rem;
            }
            
            .order-actions .btn {
                margin-bottom: 5px;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .topbar .navbar-nav {
                flex-direction: row;
            }
            
            .topbar .dropdown {
                position: static;
            }
            
            .topbar .dropdown-menu {
                position: absolute;
                right: 0;
                left: auto;
            }
            
            .btn-group .btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
            
            .main-content {
                padding: 70px 15px 15px 15px;
            }
        }
        
        /* Print styles */
        @media print {
            .sidebar, .topbar, .btn {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .order-items {
            margin-top: 10px;
        }
        
        .order-item {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .status-badge {
            font-size: 0.85em;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .order-actions {
            white-space: nowrap;
        }
        :root {
            --sidebar-width: 250px;
            --topbar-height: 60px;
            --primary-color: #28a745;
            --secondary-color: #6c757d;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #343a40;
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-left: 3px solid transparent;
            padding: 0.75rem 1.5rem;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--primary-color);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }
        
        .topbar {
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 999;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
        }
        
        .stat-card {
            border-left: 0.25rem solid var(--primary-color);
        }
        
        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
        }
        
        .table th {
            border-top: none;
        }
        
        .badge-success {
            background-color: #1cc88a;
        }
        
        .badge-warning {
            background-color: #f6c23e;
        }
        
        .badge-danger {
            background-color: #e74a3b;
        }
        
        .badge-secondary {
            background-color: #6c757d;
        }
        
        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            background: rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1rem 0;
        }
        
        .sidebar-heading {
            padding: 0 1rem;
            font-weight: 800;
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
        }
        
        .user-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        
        .img-thumbnail {
            max-width: 50px;
            max-height: 50px;
        }
        
        /* Additional styles for product management */
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
        
        
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .customer-details-table th {
            width: 30%;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-utensils"></i> <span>BENTO Cakes Admin</span>
        </div>
        
        <div class="sidebar-divider"></div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <div class="sidebar-heading">Management</div>

            <li class="nav-item">
                <a class="nav-link" href="#orders">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Manage Orders</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#categories">
                    <i class="fas fa-fw fa-tags"></i>
                    <span>Categories</span>
                </a>
            </li>

            <li class="nav-item">
    <a class="nav-link" href="#flavors">
        <i class="fas fa-fw fa-ice-cream"></i>
        <span>Manage Flavors</span>
    </a>
</li>
            
            <li class="nav-item">
                <a class="nav-link" href="#products">
                    <i class="fas fa-fw fa-hamburger"></i>
                    <span>Manage Products</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#customers">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manage Customers</span>
                </a>
            </li>
            
            <div class="sidebar-heading">Reviews & Feedback</div>
            
            <li class="nav-item">
                <a class="nav-link" href="#feedback">
                    <i class="fas fa-fw fa-comment-alt"></i>
                    <span>Customer Feedback</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#reviews">
                    <i class="fas fa-fw fa-star"></i>
                    <span>Product Reviews</span>
                </a>
            </li>
            
            <div class="sidebar-heading">Reports</div>
            
            <li class="nav-item">
                <a class="nav-link" href="#reports">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Sales Reports</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#analytics">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
     <div class="main-content" id="main-content">
    
        <!-- Topbar -->
        <nav class="topbar navbar navbar-expand navbar-light bg-white" id="topbar">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none rounded-circle mr-3" id="mobile-toggle">
                    <i class="fa fa-bars"></i>
                </button>
                
                <button class="btn btn-link d-none d-md-inline rounded-circle mr-3" id="sidebar-toggle">
                    <i class="fa fa-bars"></i>
                </button>
                
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow mx-1 d-none d-md-inline">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="badge badge-danger badge-counter">3+</span>
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown no-arrow mx-1 d-none d-md-inline">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-envelope fa-fw"></i>
                            <span class="badge badge-danger badge-counter">7</span>
                        </a>
                    </li>
                    
                    <div class="topbar-divider d-none d-sm-block"></div>
                    
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle user-dropdown" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
                            <img src="https://source.unsplash.com/QAB-WJcbgJk/60x60" class="img-profile rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                Activity Log
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <?php if(isset($_GET['message'])): ?>
                <div class="alert alert-<?php echo $_GET['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
        
            <!-- Content Row -->
            <div class="row">

<!-- Add this section after the dashboard statistics cards -->
<div class="row" id="orders">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Manage Orders</h6>
                <div class="d-flex">
                    <select class="form-control form-control-sm mr-2" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="pending_payment">Pending Payment</option>
                        <option value="paid">Paid</option>
                        <option value="preparing">Preparing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <input type="date" class="form-control form-control-sm mr-2" id="dateFilter">
                    <button class="btn btn-sm btn-primary" onclick="resetFilters()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Fetch all orders
                            $orders_query = "SELECT * FROM tbl_orders ORDER BY order_date DESC";
                            $orders_result = mysqli_query($conn, $orders_query);
                            
                            if(mysqli_num_rows($orders_result) > 0) {
                                while($order = mysqli_fetch_assoc($orders_result)): 
                            ?>
                            <tr data-status="<?php echo $order['order_status']; ?>" data-date="<?php echo date('Y-m-d', strtotime($order['order_date'])); ?>">
                                <td>#ORD-<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['contact_no']); ?></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                        switch($order['order_status']) {
                                            case 'pending_payment': echo 'badge-warning'; break;
                                            case 'paid': echo 'badge-info'; break;
                                            case 'preparing': echo 'badge-primary'; break;
                                            case 'shipped': echo 'badge-secondary'; break;
                                            case 'delivered': echo 'badge-success'; break;
                                            case 'cancelled': echo 'badge-danger'; break;
                                            default: echo 'badge-secondary';
                                        }
                                        ?> status-badge">
                                        <?php echo ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
                                    </span>
                                </td>
                                <td class="order-actions">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewOrderModal<?php echo $order['order_id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOrderModal<?php echo $order['order_id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteOrderModal<?php echo $order['order_id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; 
                            } else {
                                echo '<tr><td colspan="8" class="text-center">No orders found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


              
                
            <!-- Categories & Subcategories Section -->
            <div class="row" id="categories">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
                            <div>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus"></i> Add Category
                                </button>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="font-weight-bold mb-3">Categories</h5>
                                    <ul class="list-group">
                                        <?php 
                                        // Reset the category result pointer
                                        mysqli_data_seek($category_result, 0);
                                        while($category = mysqli_fetch_assoc($category_result)): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?php echo htmlspecialchars($category['category']); ?>
                                            <?php if(!empty($category['photo'])): ?>
                                                <img src="<?php echo htmlspecialchars($category['photo']); ?>" width="50" class="img-thumbnail">
                                            <?php endif; ?>
                                            <div>
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?php echo $category['category_id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal<?php echo $category['category_id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                
                                    <ul class="list-group">
                                    
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Add this section after the Categories section -->
<!-- Flavors Management Section -->
<div class="row" id="flavors">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Manage Flavors</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addFlavorModal">
                    <i class="fas fa-plus"></i> Add Flavor
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Flavor Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Fetch all flavors from the database
                            $flavor_query = "SELECT * FROM tbl_flavors ORDER BY flavor_name ASC";
                            $flavor_result = mysqli_query($conn, $flavor_query);
                            
                            if(mysqli_num_rows($flavor_result) > 0) {
                                while($flavor = mysqli_fetch_assoc($flavor_result)): 
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($flavor['flavor_id']); ?></td>
                                <td><?php echo htmlspecialchars($flavor['flavor_name']); ?></td>
                                <td><?php echo htmlspecialchars($flavor['description'] ?? 'No description'); ?></td>
                                <td>
                                    <?php if($flavor['is_active'] == '1'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editFlavorModal<?php echo $flavor['flavor_id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFlavorModal<?php echo $flavor['flavor_id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; 
                            } else {
                                echo '<tr><td colspan="5" class="text-center">No flavors found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Flavor Modal -->
<div class="modal fade" id="addFlavorModal" tabindex="-1" role="dialog" aria-labelledby="addFlavorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFlavorModalLabel">Add New Flavor</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="admin_home.php">
                    <input type="hidden" name="submit_flavor" value="1">
                    <div class="form-group">
                        <label>Flavor Name *</label>
                        <input type="text" class="form-control" name="flavor_name" placeholder="Enter flavor name" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" name="description" placeholder="Enter flavor description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-control" name="is_active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Flavor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit and Delete Flavor Modals -->
<?php 
// Re-fetch flavors for the modals (since the pointer was exhausted)
$flavor_query_modal = "SELECT * FROM tbl_flavors ORDER BY flavor_name ASC";
$flavor_result_modal = mysqli_query($conn, $flavor_query_modal);

if(mysqli_num_rows($flavor_result_modal) > 0) {
    while($flavor = mysqli_fetch_assoc($flavor_result_modal)): 
?>
<!-- Edit Flavor Modal -->
<div class="modal fade" id="editFlavorModal<?php echo $flavor['flavor_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Flavor</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="admin_home.php">
                    <input type="hidden" name="submit_flavor" value="1">
                    <input type="hidden" name="flavor_id" value="<?php echo $flavor['flavor_id']; ?>">
                    <div class="form-group">
                        <label>Flavor Name *</label>
                        <input type="text" class="form-control" name="flavor_name" value="<?php echo htmlspecialchars($flavor['flavor_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" name="description"><?php echo htmlspecialchars($flavor['description'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-control" name="is_active" required>
                            <option value="1" <?php echo ($flavor['is_active'] == '1') ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo ($flavor['is_active'] == '0') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Flavor Modal -->
<div class="modal fade" id="deleteFlavorModal<?php echo $flavor['flavor_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the flavor "<?php echo htmlspecialchars($flavor['flavor_name']); ?>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="admin_home.php?delete_flavor=<?php echo $flavor['flavor_id']; ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<?php 
    endwhile;
}
?>

            <!-- Manage Products Section -->
            <div class="row" id="products">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Manage Products</h6>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="fas fa-plus"></i> Add Product
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Reset the product result pointer
                                        mysqli_data_seek($product_result, 0);
                                        while($product = mysqli_fetch_assoc($product_result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product['productmaster_id']); ?></td>
                                            <td>
                                                <?php if(!empty($product['image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-thumbnail" width="50">
                                                <?php else: ?>
                                                    <img src="https://via.placeholder.com/50" class="img-thumbnail" width="50">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                            <td><?php echo htmlspecialchars($product['category'] ?? 'N/A'); ?></td>
                                            
                                            <td><?php echo htmlspecialchars(substr($product['description'], 0, 50)); ?>...</td>
                                            <td>₹<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                                            <td>
                                                <?php if($product['status'] == 'available'): ?>
                                                    <span class="badge badge-success">Available</span>
                                                <?php elseif($product['status'] == 'out_of_stock'): ?>
                                                    <span class="badge badge-danger">Out of Stock</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $product['status']))); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product['productmaster_id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?php echo $product['productmaster_id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Manage Customers Section -->
            <div class="row" id="customers">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Manage Customers</h6>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Customer ID</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Orders</th>
                                            <th>Last Order</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Reset the customer result pointer
                                        mysqli_data_seek($customer_result, 0);
                                        while($customer = mysqli_fetch_assoc($customer_result)): 
                                            // Count orders for this customer (you would replace this with actual query)
                                            $orders_query = "SELECT COUNT(*) as order_count FROM tbl_orders WHERE customer_id = '{$customer['customer_id']}'";
                                            $orders_result = mysqli_query($conn, $orders_query);
                                            $orders_data = mysqli_fetch_assoc($orders_result);
                                            $order_count = $orders_data['order_count'];
                                            
                                            // Get last order date (you would replace this with actual query)
                                            $last_order_query = "SELECT order_date FROM tbl_orders WHERE customer_id = '{$customer['customer_id']}' ORDER BY order_date DESC LIMIT 1";
                                            $last_order_result = mysqli_query($conn, $last_order_query);
                                            $last_order = mysqli_num_rows($last_order_result) > 0 ? mysqli_fetch_assoc($last_order_result)['order_date'] : 'Never';
                                        ?>
                                        <tr>
                                            <td>CUST-<?php echo str_pad($customer['customer_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                            <td>
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($customer['name']); ?>&background=random" class="customer-avatar">
                                            </td>
                                            <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['contact_no']); ?></td>
                                            <td><?php echo $order_count; ?></td>
                                            <td><?php echo $last_order; ?></td>
                                            <td>
    <?php if($customer['login_status'] == 'active'): ?>
        <span class="badge badge-success">Active</span>
    <?php else: ?>
        <span class="badge badge-secondary">Inactive</span>
    <?php endif; ?>
</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewCustomerModal<?php echo $customer['customer_id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCustomerModal<?php echo $customer['customer_id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal<?php echo $customer['customer_id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            
            <!-- Reports Section -->
            <div class="row" id="reports">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Sales Reports</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Report Type</label>
                                        <select class="form-control">
                                            <option>Sales Summary</option>
                                            <option>Product Performance</option>
                                            <option>Customer Orders</option>
                                            <option>Branch Performance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold text-primary">Sales by Category</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="categoryChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold text-primary">Monthly Sales</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="monthlySalesChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Category</th>
                                                    <th>Quantity Sold</th>
                                                    <th>Total Revenue</th>
                                                    <th>Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Spicy Chicken Pizza</td>
                                                    <td>Pizza</td>
                                                    <td>156</td>
                                                    <td>$2,024.40</td>
                                                    <td>4.2</td>
                                                </tr>
                                                <tr>
                                                    <td>Vegan Burger</td>
                                                    <td>Burgers</td>
                                                    <td>128</td>
                                                    <td>$1,278.72</td>
                                                    <td>4.8</td>
                                                </tr>
                                                <tr>
                                                    <td>Pasta Alfredo</td>
                                                    <td>Pasta</td>
                                                    <td>98</td>
                                                    <td>$1,127.00</td>
                                                    <td>4.5</td>
                                                </tr>
                                                <tr>
                                                    <td>Chocolate Cheesecake</td>
                                                    <td>Desserts</td>
                                                    <td>87</td>
                                                    <td>$565.50</td>
                                                    <td>4.0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    
    <!-- View Customer Modals -->
    <?php 
    // Reset the customer result pointer to regenerate the modals
    mysqli_data_seek($customer_result, 0);
    while($customer = mysqli_fetch_assoc($customer_result)): 
    ?>
    <div class="modal fade" id="viewCustomerModal<?php echo $customer['customer_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Details</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($customer['name']); ?>&background=random&size=150" class="img-thumbnail rounded-circle mb-3">
                            <h5><?php echo htmlspecialchars($customer['name']); ?></h5>
                            <span class="badge <?php echo $customer['login_status'] == 'active' ? 'badge-success' : 'badge-secondary'; ?>">
                                <?php echo ucfirst($customer['login_status']); ?>
                            </span>
                        </div>
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table customer-details-table">
                                    <tr>
                                        <th>Customer ID:</th>
                                        <td>CUST-<?php echo str_pad($customer['customer_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td><?php echo htmlspecialchars($customer['contact_no']); ?></td>
                                    </tr>
                                    <tr>
                                        <tr>
    <th>Registration Date:</th>
    <td><?php echo isset($customer['created_at']) ? date('M d, Y', strtotime($customer['created_at'])) : 'Not available'; ?></td>
</tr>
<tr>
    <th>Total Orders:</th>
    <td>
        <?php
        $order_count = 0;
        if(isset($customer['customer_id'])) {
            $orders_query = "SELECT COUNT(*) as order_count FROM tbl_orders WHERE customer_id = '{$customer['customer_id']}'";
            $orders_result = mysqli_query($conn, $orders_query);
            if($orders_result) {
                $orders_data = mysqli_fetch_assoc($orders_result);
                $order_count = $orders_data['order_count'] ?? 0;
            }
        }
        echo $order_count;
        ?>
    </td>
</tr>
<tr>
    <th>Total Spent:</th>
    <td>
        <?php
        $total_spent = 0.00;
        if(isset($customer['customer_id'])) {
            $spent_query = "SELECT SUM(total_amount) as total_spent FROM tbl_orders WHERE customer_id = '{$customer['customer_id']}'";
            $spent_result = mysqli_query($conn, $spent_query);
            if($spent_result) {
                $spent_data = mysqli_fetch_assoc($spent_result);
                $total_spent = $spent_data['total_spent'] ?? 0.00;
            }
        }
        echo '$' . number_format($total_spent, 2);
        ?>
    </td>
</tr>
<tr>
    <th>Last Order:</th>
    <td>
        <?php
        $last_order = 'No orders yet';
        if(isset($customer['customer_id'])) {
            $last_order_query = "SELECT order_date FROM tbl_orders WHERE customer_id = '{$customer['customer_id']}' ORDER BY order_date DESC LIMIT 1";
            $last_order_result = mysqli_query($conn, $last_order_query);
            if($last_order_result && mysqli_num_rows($last_order_result) > 0) {
                $last_order_data = mysqli_fetch_assoc($last_order_result);
                $last_order = date('M d, Y', strtotime($last_order_data['order_date']));
            }
        }
        echo $last_order;
        ?>
    </td>
</tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="mb-3">Recent Orders</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $recent_orders_query = "SELECT * FROM tbl_orders WHERE customer_id = '{$customer['customer_id']}' ORDER BY order_date DESC LIMIT 5";
                                $recent_orders_result = mysqli_query($conn, $recent_orders_query);
                                
                                if(mysqli_num_rows($recent_orders_result) > 0) {
                                    while($order = mysqli_fetch_assoc($recent_orders_result)) {
                                        echo '<tr>';
                                        echo '<td>ORD-' . str_pad($order['order_id'], 4, '0', STR_PAD_LEFT) . '</td>';
                                        echo '<td>' . date('M d, Y', strtotime($order['order_date'])) . '</td>';
                                        echo '<td>' . $order['item_count'] . '</td>';
                                        echo '<td>$' . number_format($order['total_amount'], 2) . '</td>';
                                        echo '<td><span class="badge badge-' . 
                                             ($order['status'] == 'delivered' ? 'success' : 
                                              ($order['status'] == 'processing' ? 'warning' : 'danger')) . '">' . 
                                             ucfirst(str_replace('_', ' ', $order['status'])) . '</span></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">No orders found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal<?php echo $customer['customer_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="admin_home.php">
                        <input type="hidden" name="update_customer_status" value="1">
                        <input type="hidden" name="login_id" value="<?php echo $customer['login_id']; ?>">
                        
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($customer['name']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($customer['contact_no']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="active" <?php echo $customer['login_status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $customer['login_status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Customer Modal -->
    <div class="modal fade" id="deleteCustomerModal<?php echo $customer['customer_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the customer "<?php echo htmlspecialchars($customer['name']); ?>"?</p>
                    <p class="text-danger">This will permanently delete all customer data including their order history!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="admin_home.php?delete_customer=<?php echo $customer['customer_id']; ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
    
    
    <!-- Order Modals -->
    <?php 
    // Reset the orders result pointer to regenerate the modals
    mysqli_data_seek($orders_result, 0);
    while($order = mysqli_fetch_assoc($orders_result)): 
        // Get order items
        $order_items_query = "SELECT oi.*, p.product_name, p.image 
                              FROM tbl_order_items oi 
                              JOIN tbl_products p ON oi.product_id = p.productmaster_id 
                              WHERE oi.order_id = '{$order['order_id']}'";
        $order_items_result = mysqli_query($conn, $order_items_query);
    ?>
    <!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details - #ORD-<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['contact_no']); ?></p>
                        
                        <!-- Address Details -->
                        <h6 class="mt-3">Shipping Address</h6>
                        <p class="mb-0"><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                        <?php if(!empty($order['landmark'])): ?>
                            <p class="mb-0"><strong>Landmark:</strong> <?php echo htmlspecialchars($order['landmark']); ?></p>
                        <?php endif; ?>
                        <?php if(!empty($order['apartment_no'])): ?>
                            <p class="mb-0"><strong>Apartment No:</strong> <?php echo htmlspecialchars($order['apartment_no']); ?></p>
                        <?php endif; ?>
                        <p class="mb-0"><strong>City:</strong> <?php echo htmlspecialchars($order['city']); ?></p>
                        <p class="mb-0"><strong>Postcode:</strong> <?php echo htmlspecialchars($order['postcode']); ?></p>
                        <p class="mb-0"><strong>Shipping Method:</strong> <?php echo htmlspecialchars($order['shipping_method']); ?></p>
                        
                        <?php if(!empty($order['cake_message'])): ?>
                            <p class="mb-0 mt-2"><strong>Cake Message:</strong> <?php echo htmlspecialchars($order['cake_message']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge 
                                <?php 
                                if($order['order_status'] == 'pending_payment') echo 'badge-warning';
                                elseif($order['order_status'] == 'paid') echo 'badge-info';
                                elseif($order['order_status'] == 'preparing') echo 'badge-primary';
                                elseif($order['order_status'] == 'shipped') echo 'badge-secondary';
                                elseif($order['order_status'] == 'delivered') echo 'badge-success';
                                else echo 'badge-danger';
                                ?> status-badge">
                                <?php echo ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
                            </span>
                        </p>
                        <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
                        <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
                    </div>
                </div>
                
                <h6>Order Item</h6>
                <div class="order-details">
                    <div class="order-item d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                        <div class="d-flex align-items-center">
                            <?php if(!empty($order['image'])): ?>
                                <img src="<?php echo htmlspecialchars($order['image']); ?>" class="img-thumbnail mr-3" width="50">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/50" class="img-thumbnail mr-3" width="50">
                            <?php endif; ?>
                            <div>
                                <?php 
                                // You'll need to fetch the product name from tbl_products
                                // This should be done in your main query when fetching orders
                                $product_name = "Product #" . $order['product_id']; // Placeholder
                                ?>
                                <p class="mb-0"><?php echo htmlspecialchars($product_name); ?></p>
                                <small class="text-muted">Quantity: <?php echo $order['quantity']; ?></small>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="mb-0">₹<?php echo number_format($order['total_amount'], 2); ?></p>
                            <small class="text-muted">₹<?php echo number_format($order['total_amount'] / $order['quantity'], 2); ?> each</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- Edit Order Modal -->
    <div class="modal fade" id="editOrderModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="admin_home.php">
                        <input type="hidden" name="update_order_status" value="1">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        
                        <div class="form-group">
                            <label>Order ID</label>
                            <input type="text" class="form-control" value="#ORD-<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Customer</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($order['customer_name']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="text" class="form-control" value="₹<?php echo number_format($order['total_amount'], 2); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $order['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="preparing" <?php echo $order['status'] == 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                                <option value="out_for_delivery" <?php echo $order['status'] == 'out_for_delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Order Modal -->
    <div class="modal fade" id="deleteOrderModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete order #ORD-<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?>?</p>
                    <p class="text-danger">This action cannot be undone and will permanently delete all order data!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="admin_home.php?delete_order=<?php echo $order['order_id']; ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="save_category.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" name="category_name" placeholder="Enter category name" required>
                        </div>
                        <div class="form-group">
                            <label>Category Image</label>
                            <input type="file" class="form-control-file" name="category_image" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                <!-- Flavors Tab -->
                            <div class="tab-pane fade" id="flavors" role="tabpanel">
                                <div class="mt-4">
                                    <h5>Add Flavors to This Category</h5>
                                    <p class="text-muted">Add one or more flavors that belong to this category.</p>
                                    
                                    <div id="flavor-list" class="flavor-list mb-4">
                                        <!-- Flavors will be added here dynamically -->
                                        <div class="alert alert-info">No flavors added yet. Click the button below to add flavors.</div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-primary mb-3" id="add-flavor-btn">
                                        <i class="fas fa-plus me-2"></i>Add Flavor
                                    </button>
                                    
                                    <!-- Flavor Form (initially hidden) -->
                                    <div id="flavor-form" class="flavor-item mb-3" style="display: none;">
                                        <h6>New Flavor</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label class="form-label">Flavor Name</label>
                                                    <input type="text" class="form-control flavor-name" placeholder="Enter flavor name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label class="form-label">Flavor Image</label>
                                                    <input type="file" class="form-control flavor-image">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control flavor-description" rows="2" placeholder="Enter flavor description"></textarea>
                                        </div>
                                        <div class="flavor-actions">
                                            <button type="button" class="btn btn-sm btn-success save-flavor">Save Flavor</button>
                                            <button type="button" class="btn btn-sm btn-secondary cancel-flavor">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit and Delete Category Modals -->
    <?php 
    // Reset the category result pointer to regenerate the modals
    mysqli_data_seek($category_result, 0);
    while($category = mysqli_fetch_assoc($category_result)): 
    ?>
    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal<?php echo $category['category_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="edit_category.php" enctype="multipart/form-data">
                        <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" name="category_name" value="<?php echo htmlspecialchars($category['category']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Current Image</label><br>
                            <?php if(!empty($category['photo'])): ?>
                                <img src="<?php echo htmlspecialchars($category['photo']); ?>" width="100" class="img-thumbnail mb-2">
                            <?php endif; ?>
                            <label>Change Image (leave blank to keep current)</label>
                            <input type="file" class="form-control-file" name="category_image">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal<?php echo $category['category_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the category "<?php echo htmlspecialchars($category['category']); ?>"?</p>
                    <p class="text-danger">This will also delete all products and subcategories under this category!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="delete_category.php" style="display: inline;">
                        <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
    
       <?php 
                                // Reset the category result pointer
                                mysqli_data_seek($category_result, 0);
                                while($category = mysqli_fetch_assoc($category_result)): ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    

                                <?php 
                                // Reset the category result pointer
                                mysqli_data_seek($category_result, 0);
                                while($category = mysqli_fetch_assoc($category_result)): ?>
                                    <option value="<?php echo $category['category_id']; ?>" >
                                        <?php echo htmlspecialchars($category['category']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                       
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="admin_home.php" enctype="multipart/form-data">
                    <input type="hidden" name="submit_product" value="1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" class="form-control" name="product_name" placeholder="Enter product name" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="category_id" id="category_select" required>
                                    <option value="">Select Category</option>
                                    <?php 
                                    // Reset the category result pointer
                                    mysqli_data_seek($category_result, 0);
                                    while($category = mysqli_fetch_assoc($category_result)): ?>
                                        <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" class="form-control" name="price" placeholder="Enter price" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="description" placeholder="Enter description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" class="form-control-file" name="image" required>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="available">Available</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="coming_soon">Coming Soon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Flavors Section -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label><strong>Available Flavors</strong></label>
                                <div class="flavors-container" style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                                    <?php
                                    // Fetch all flavors from tbl_flavors
                                    $flavors_query = "SELECT * FROM tbl_flavors ORDER BY flavor_name";
                                    $flavors_result = mysqli_query($conn, $flavors_query);
                                    
                                    if(mysqli_num_rows($flavors_result) > 0) {
                                        while($flavor = mysqli_fetch_assoc($flavors_result)) {
                                            echo '<div class="form-check">';
                                            echo '<input class="form-check-input" type="checkbox" name="flavors[]" value="' . $flavor['flavor_id'] . '" id="flavor_' . $flavor['flavor_id'] . '">';
                                            echo '<label class="form-check-label" for="flavor_' . $flavor['flavor_id'] . '">' . htmlspecialchars($flavor['flavor_name']) . '</label>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<p class="text-muted">No flavors available. Please add flavors first.</p>';
                                    }
                                    ?>
                                </div>
                                <small class="form-text text-muted">Select the flavors available for this product</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
    <!-- Edit Product Modals -->
<?php 
// Reset the product result pointer to regenerate the modals
mysqli_data_seek($product_result, 0);
while($product = mysqli_fetch_assoc($product_result)): 
    
    // Fetch currently selected flavors for this product
    $current_flavors_query = "SELECT f.flavor_id 
                             FROM tbl_flavors f 
                             INNER JOIN product_flavors pf ON f.flavor_id = pf.flavor_id 
                             WHERE pf.product_id = '" . $product['productmaster_id'] . "'";
    $current_flavors_result = mysqli_query($conn, $current_flavors_query);
    $current_flavor_ids = array();
    while($current_flavor = mysqli_fetch_assoc($current_flavors_result)) {
        $current_flavor_ids[] = $current_flavor['flavor_id'];
    }
?>
<div class="modal fade" id="editProductModal<?php echo $product['productmaster_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="admin_home.php" enctype="multipart/form-data">
                    <input type="hidden" name="submit_product" value="1">
                    <input type="hidden" name="product_id" value="<?php echo $product['productmaster_id']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="category_id" id="category_select_<?php echo $product['productmaster_id']; ?>" required>
                                    <option value="">Select Category</option>
                                    <?php 
                                    // Reset the category result pointer
                                    mysqli_data_seek($category_result, 0);
                                    while($category = mysqli_fetch_assoc($category_result)): ?>
                                        <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['category']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" class="form-control" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Current Image</label><br>
                                <?php if(!empty($product['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="product-image mb-2" style="max-width: 100px; max-height: 100px;">
                                <?php endif; ?>
                                <label>Change Image (leave blank to keep current)</label>
                                <input type="file" class="form-control-file" name="image">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="available" <?php echo ($product['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                                    <option value="out_of_stock" <?php echo ($product['status'] == 'out_of_stock') ? 'selected' : ''; ?>>Out of Stock</option>
                                    <option value="coming_soon" <?php echo ($product['status'] == 'coming_soon') ? 'selected' : ''; ?>>Coming Soon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Flavors Section -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label><strong>Available Flavors</strong></label>
                                <div class="flavors-container" style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                                    <?php
                                    // Fetch all flavors from tbl_flavors
                                    $flavors_query = "SELECT * FROM tbl_flavors ORDER BY flavor_name";
                                    $flavors_result = mysqli_query($conn, $flavors_query);
                                    
                                    if(mysqli_num_rows($flavors_result) > 0) {
                                        while($flavor = mysqli_fetch_assoc($flavors_result)) {
                                            $is_checked = in_array($flavor['flavor_id'], $current_flavor_ids) ? 'checked' : '';
                                            echo '<div class="form-check">';
                                            echo '<input class="form-check-input" type="checkbox" name="flavors[]" value="' . $flavor['flavor_id'] . '" id="flavor_' . $product['productmaster_id'] . '_' . $flavor['flavor_id'] . '" ' . $is_checked . '>';
                                            echo '<label class="form-check-label" for="flavor_' . $product['productmaster_id'] . '_' . $flavor['flavor_id'] . '">' . htmlspecialchars($flavor['flavor_name']) . '</label>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<p class="text-muted">No flavors available. Please add flavors first.</p>';
                                    }
                                    ?>
                                </div>
                                <small class="form-text text-muted">Select the flavors available for this product</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
    <!-- Delete Product Modal -->
    <div class="modal fade" id="deleteProductModal<?php echo $product['productmaster_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the product "<?php echo htmlspecialchars($product['product_name']); ?>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="admin_home.php?delete_product=<?php echo $product['productmaster_id']; ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>


    
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sample charts - you would replace with real data from your backend
        document.addEventListener('DOMContentLoaded', function() {
            // Category Chart
            var ctx = document.getElementById('categoryChart').getContext('2d');
            var categoryChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pizza', 'Burgers', 'Pasta', 'Desserts', 'Drinks'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b'
                        ],
                        hoverBackgroundColor: [
                            '#2e59d9',
                            '#17a673',
                            '#2c9faf',
                            '#dda20a',
                            '#be2617'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    },
                    cutout: '70%',
                },
            });
            
            // Monthly Sales Chart
            var ctx2 = document.getElementById('monthlySalesChart').getContext('2d');
            var monthlySalesChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: "Revenue",
                        backgroundColor: "#4e73df",
                        hoverBackgroundColor: "#2e59d9",
                        borderColor: "#4e73df",
                        data: [4215, 5312, 6251, 7841, 9821, 14984, 12000, 11000, 9800, 11200, 12500, 15000],
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            
            
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle functionality
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const topbar = document.getElementById('topbar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileToggle = document.getElementById('mobile-toggle');
            
            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    topbar.classList.toggle('expanded');
                });
            }
            
            // Mobile sidebar toggle
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992 && 
                    !sidebar.contains(event.target) && 
                    !mobileToggle.contains(event.target) &&
                    sidebar.classList.contains('mobile-show')) {
                    sidebar.classList.remove('mobile-show');
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('mobile-show');
                }
            });
        })

        
    </script>
</body>
</html>
<?php
// Close database connection
mysqli_close($conn);
?>