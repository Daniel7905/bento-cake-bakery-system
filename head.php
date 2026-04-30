<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bento Cake | Food Delivery Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --primary-color: #fcc4d4;
            --secondary-color: #5a0d24;
            --accent-color: #ff85a2;
            --text-light: #f8d7da;
            --text-dark: #5a0d24;
        }
        
        body {
            background-color: var(--primary-color);
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header Styles */
        header {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(90, 13, 36, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        .logo span {
            color: var(--accent-color);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 25px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--accent-color);
        }
        
        .nav-icons {
            display: flex;
            align-items: center;
        }
        
        .nav-icons a {
            color: var(--text-dark);
            margin-left: 15px;
            font-size: 18px;
            transition: color 0.3s;
        }
        
        .nav-icons a:hover {
            color: var(--accent-color);
        }
        
        /* Slider Styles */
        .about-us-slider {
            height: 500px;
            position: relative;
            margin-bottom: 40px;
        }
        
        .swiper-slide {
            position: relative;
            overflow: hidden;
        }
        
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .transform-center {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            transform: translateY(-50%);
            z-index: 2;
        }
        
        .right-side-content {
            padding: 20px;
            background-color: rgba(90, 13, 36, 0.7);
            border-radius: 10px;
            max-width: 600px;
        }
        
        .right-side-content h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .right-side-content h3 {
            color: white;
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: 400;
        }
        
        .overlay-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(90, 13, 36, 0.3);
            z-index: 1;
        }
        
        .swiper-button-next, .swiper-button-prev {
            color: var(--secondary-color) !important;
            background: rgba(252, 196, 212, 0.8);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 24px;
        }
        
        /* Button Styles */
        .btn-second, .btn-submit, .btn-first {
            background-color: var(--secondary-color);
            color: var(--primary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .btn-second:hover, .btn-submit:hover, .btn-first:hover {
            background-color: #3a0818;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.3);
        }
        
        /* Section Styles */
        .section-padding {
            padding: 60px 0;
        }
        
        .u-line {
            border-bottom: 1px solid var(--secondary-color);
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        
        .section-header-left {
            margin-bottom: 30px;
        }
        
        .section-header-left .title {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .text-light-black {
            color: var(--text-dark);
        }
        
        .text-light-white {
            color: var(--text-dark);
        }
        
        /* Category Styles */
        .category-slider {
            padding: 20px 0;
        }
        
        .categories {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s;
        }
        
        .categories:hover {
            transform: translateY(-5px);
        }
        
        .category-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 30px;
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }
        
        .cat-name {
            font-weight: 500;
        }
        
        /* Product Styles */
        .product-box {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(90, 13, 36, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        
        .product-box:hover {
            transform: translateY(-5px);
        }
        
        .product-img {
            height: 250px;
            overflow: hidden;
        }
        
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-box:hover .product-img img {
            transform: scale(1.1);
        }
        
        .product-caption {
            padding: 15px;
        }
        
        .product-title a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
        }
        
        .price-time {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        
        .price {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .view-all-btn {
            text-align: center;
            margin-top: 30px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: var(--text-dark);
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.7;
        }
        
        /* Footer Styles */
        .footer-top {
            background-color: var(--secondary-color);
            padding: 40px 0;
        }
        
        .icon-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .icon-box span:first-child {
            font-size: 30px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .text-custom-white {
            color: var(--primary-color);
        }
        
        footer {
            background-color: var(--secondary-color);
            color: var(--primary-color);
            padding: 20px 0;
            text-align: center;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .right-side-content h1 {
                font-size: 2rem;
            }
            
            .right-side-content h3 {
                font-size: 1.2rem;
            }
            
            .nav-links {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .about-us-slider {
                height: 400px;
            }
            
            .right-side-content {
                max-width: 100%;
            }
            
            .section-header-left .title {
                font-size: 1.8rem;
            }
            
            .category-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
        }
        
        @media (max-width: 576px) {
            .about-us-slider {
                height: 300px;
            }
            
            .right-side-content h1 {
                font-size: 1.5rem;
            }
            
            .btn-second, .btn-submit, .btn-first {
                padding: 10px 20px;
            }
            
            .section-padding {
                padding: 40px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="#" class="logo">Bento<span>Cake</span></a>
                
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Menu</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                
                <div class="nav-icons">
                    <a href="#"><i class="fas fa-search"></i></a>
                    <a href="#"><i class="fas fa-shopping-cart"></i></a>
                    <a href="#"><i class="fas fa-user"></i></a>
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Slider -->
    <section class="about-us-slider swiper-container p-relative">
        <div class="swiper-wrapper">
            <div class="swiper-slide slide-item">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1080&q=80" class="img-fluid full-width" alt="Bento Cakes">
                <div class="transform-center">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 align-self-center">
                                <div class="right-side-content text-center">
                                    <h1 class="text-custom-white fw-600">Delicious Bento Cakes</h1>
                                    <h3 class="text-custom-white fw-400">Perfect for any occasion</h3>
                                    <a href="#" class="btn-second btn-submit">Order Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay overlay-bg"></div>
            </div>
            
            <div class="swiper-slide slide-item">
                <img src="https://images.unsplash.com/photo-1559620192-032c4bc4674e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1080&q=80" class="img-fluid full-width" alt="Custom Cakes">
                <div class="transform-center">
                    <div class="container">
                        <div class="row justify-content-end">
                            <div class="col-lg-7 align-self-center">
                                <div class="right-side-content text-right">
                                    <h1 class="text-custom-white fw-600">Custom Designs</h1>
                                    <h3 class="text-custom-white fw-400">Personalized just for you</h3>
                                    <a href="#" class="btn-second btn-submit">Customize</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay overlay-bg"></div>
            </div>
            
            <div class="swiper-slide slide-item">
                <img src="https://images.unsplash.com/photo-1562440499-64c9a111f713?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1080&q=80" class="img-fluid full-width" alt="Delivery">
                <div class="transform-center">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-lg-7 align-self-center">
                                <div class="right-side-content text-left">
                                    <h1 class="text-custom-white fw-600">Fast Delivery</h1>
                                    <h3 class="text-custom-white fw-400">Fresh to your doorstep</h3>
                                    <a href="#" class="btn-second btn-submit">Order Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay overlay-bg"></div>
            </div>
        </div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </section>
    
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
                            <div class="swiper-slide">
                                <a href="#" class="categories">
                                    <div class="category-icon bg-light-green"> 
                                        <i class="fas fa-birthday-cake"></i>
                                    </div> 
                                    <span class="text-light-black cat-name">Birthday</span>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="categories">
                                    <div class="category-icon bg-light-green"> 
                                        <i class="fas fa-heart"></i>
                                    </div> 
                                    <span class="text-light-black cat-name">Anniversary</span>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="categories">
                                    <div class="category-icon bg-light-green"> 
                                        <i class="fas fa-graduation-cap"></i>
                                    </div> 
                                    <span class="text-light-black cat-name">Graduation</span>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="categories">
                                    <div class="category-icon bg-light-green"> 
                                        <i class="fas fa-baby"></i>
                                    </div> 
                                    <span class="text-light-black cat-name">Baby Shower</span>
                                </a>
                            </div>
                            <div class="swiper-slide">
                                <a href="#" class="categories">
                                    <div class="category-icon bg-light-green"> 
                                        <i class="fas fa-ring"></i>
                                    </div> 
                                    <span class="text-light-black cat-name">Wedding</span>
                                </a>
                            </div>
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Birthday Cakes -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header-left">
                        <h3 class="text-light-black header-title title">Birthday Cakes</h3>
                        <p class="text-light-white">Make your special day even sweeter with our delicious birthday bento cakes</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product-box mb-xl-20">
                        <div class="product-img">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1596223575327-99a5cdb1621d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="img-fluid full-width" alt="Chocolate Birthday Cake" style="height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="product-caption">
                            <div class="title-box">
                                <h6 class="product-title">
                                    <a href="#" class="text-light-black">
                                        Chocolate Bliss
                                    </a>
                                </h6>
                            </div>
                            <p class="text-light-white">Rich chocolate cake with creamy chocolate frosting and sprinkles</p>
                            <div class="product-details">
                                <div class="price-time">
                                    <span class="text-light-white price">₹899.00</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="btn btn-first btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product-box mb-xl-20">
                        <div class="product-img">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1586788680434-30d8b2f0466e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="img-fluid full-width" alt="Vanilla Birthday Cake" style="height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="product-caption">
                            <div class="title-box">
                                <h6 class="product-title">
                                    <a href="#" class="text-light-black">
                                        Vanilla Dream
                                    </a>
                                </h6>
                            </div>
                            <p class="text-light-white">Light vanilla cake with buttercream frosting and fresh flowers</p>
                            <div class="product-details">
                                <div class="price-time">
                                    <span class="text-light-white price">₹849.00</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="btn btn-first btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product-box mb-xl-20">
                        <div class="product-img">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1574085733277-851d9d856a3a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="img-fluid full-width" alt="Strawberry Birthday Cake" style="height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="product-caption">
                            <div class="title-box">
                                <h6 class="product-title">
                                    <a href="#" class="text-light-black">
                                        Berry Delight
                                    </a>
                                </h6>
                            </div>
                            <p class="text-light-white">Vanilla cake with fresh strawberry filling and cream cheese frosting</p>
                            <div class="product-details">
                                <div class="price-time">
                                    <span class="text-light-white price">₹949.00</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="btn btn-first btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="view-all-btn">
                <a href="#" class="btn btn-first">View All Birthday Cakes</a>
            </div>
        </div>
    </section>
    
    <!-- Anniversary Cakes -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header-left">
                        <h3 class="text-light-black header-title title">Anniversary Cakes</h3>
                        <p class="text-light-white">Celebrate your love with our specially designed anniversary cakes</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product-box mb-xl-20">
                        <div class="product-img">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="img-fluid full-width" alt="Red Velvet Anniversary Cake" style="height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="product-caption">
                            <div class="title-box">
                                <h6 class="product-title">
                                    <a href="#" class="text-light-black">
                                        Red Velvet Romance
                                    </a>
                                </h6>
                            </div>
                            <p class="text-light-white">Classic red velvet cake with cream cheese frosting and edible gold details</p>
                            <div class="product-details">
                                <div class="price-time">
                                    <span class="text-light-white price">₹999.00</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="btn btn-first btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product-box mb-xl-20">
                        <div class="product-img">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1517686469429-8bdb88b9f907?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="img-fluid full-width" alt="Elegant Anniversary Cake" style="height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="product-caption">
                            <div class="title-box">
                                <h6 class="product-title">
                                    <a href="#" class="text-light-black">
                                        Golden Love
                                    </a>
                                </h6>
                            </div>
                            <p class="text-light-white">Vanilla bean cake with raspberry filling and white chocolate frosting</p>
                            <div class="product-details">
                                <div class="price-time">
                                    <span class="text-light-white price">₹1,099.00</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="btn btn-first btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="product-box mb-xl-20">
                        <div class="product-img">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1517433670267-08bbd4be890f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="img-fluid full-width" alt="Chocolate Anniversary Cake" style="height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="product-caption">
                            <div class="title-box">
                                <h6 class="product-title">
                                    <a href="#" class="text-light-black">
                                        Decadent Duo
                                    </a>
                                </h6>
                            </div>
                            <p class="text-light-white">Chocolate and vanilla marble cake with chocolate ganache and fresh berries</p>
                            <div class="product-details">
                                <div class="price-time">
                                    <span class="text-light-white price">₹1,199.00</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="btn btn-first btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="view-all-btn">
                <a href="#" class="btn btn-first">View All Anniversary Cakes</a>
            </div>
        </div>
    </section>
    
    <!-- Footer Top -->
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
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 Bento Cake. All Rights Reserved.</p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html>