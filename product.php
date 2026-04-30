<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selection | Bento Cake</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .footer-top {
            background-color: #5a0d24 !important;
        }
        .footer-top .text-custom-white {
            color: #fcc4d4 !important;
        }
        .footer-top .text-light-green {
            color: #fcc4d4 !important;
        }
        /* Your existing CSS styles */
        header{
            background-color: #fcc4d4;
        }
        body {
            background-color: #fcc4d4;
            color: #5a0d24;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
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
        
        /* Product Details Section Styles */
        .product-details-container {
            max-width: 1200px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.1);
        }
        
        @media (max-width: 992px) {
            .product-details-container {
                grid-template-columns: 1fr;
            }
        }
        
        .product-image-section {
            position: relative;
        }
        
        .product-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .product-info-section {
            padding: 20px;
        }
        
        .product-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #5a0d24;
        }
        
        .product-price {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #5a0d24;
        }
        
        .product-description {
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .product-features {
            margin-bottom: 25px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .feature-icon {
            margin-right: 10px;
            color: #5a0d24;
        }
        
        /* Product Selection Form Styles */
        .product-selection-container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.1);
        }
        
        .product-price {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
            color: #5a0d24;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin: 25px 0 15px;
            color: #5a0d24;
        }
        
        .options-group {
            margin-bottom: 20px;
        }
        
        .option-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .options-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .option-item {
            background: #f8f8f8;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .option-item.selected {
            background: #5a0d24;
            color: #fcc4d4;
            border-color: #5a0d24;
        }
        
        .divider {
            height: 1px;
            background: #ddd;
            margin: 25px 0;
        }
        
        .message-section textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }
        
        .char-count {
            text-align: right;
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }
        
        .quantity-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }
        
        .quantity-btn {
            background: #5a0d24;
            color: #fcc4d4;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-input {
            width: 60px;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 18px;
        }
        
        .btn-buy {
            background: #5a0d24;
            color: #fcc4d4;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
            margin-top: 20px;
        }
        
        .btn-buy:hover {
            background: #3a0818;
        }
        
        /* Checkout specific styles */
        .checkout-container {
            background-color: #fcc4d4;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: none; /* Hidden by default */
        }
        
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 992px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .form-section, .product-section {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.1);
        }
        
        .form-header, .product-header {
            border-bottom: 2px solid #5a0d24;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            color: #5a0d24;
            font-size: 16px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #5a0d24;
            box-shadow: 0 0 0 2px rgba(90, 13, 36, 0.2);
        }
        
        .radio-group {
            display: flex;
            gap: 20px;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-buy-now {
            background-color: #5a0d24;
            color: #fcc4d4;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .btn-buy-now:hover {
            background-color: #3a0818;
        }
        
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .error {
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .required-field::after {
            content: " *";
            color: #ff0000;
        }
        
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
        
        /* New styles for redesigned form */
        .order-summary {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .order-summary-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #5a0d24;
        }
        
        .order-summary-price {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            color: #5a0d24;
        }
        
        .section-divider {
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }
        
        .contact-section, .delivery-section {
            margin-bottom: 25px;
        }
        
        .section-title-form {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #5a0d24;
        }
        
        .sign-in-link {
            color: #5a0d24;
            text-decoration: underline;
            font-weight: bold;
        }
        
        .whatsapp-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .country-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .country-flag {
            width: 24px;
            height: 16px;
            border-radius: 2px;
        }
        
        .name-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 576px) {
            .name-fields {
                grid-template-columns: 1fr;
            }
        }
        
        .address-line {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .city-state-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 576px) {
            .city-state-fields {
                grid-template-columns: 1fr;
            }
        }
        
        .save-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .payment-section {
            margin-top: 30px;
        }
        
        .payment-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #5a0d24;
        }
        
        /* Additional styles for product details */
        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stars {
            color: #ffc107;
            margin-right: 10px;
        }
        
        .rating-count {
            color: #777;
        }
        
        .product-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .meta-item {
            text-align: center;
        }
        
        .meta-value {
            font-weight: bold;
            font-size: 18px;
        }
        
        .meta-label {
            font-size: 14px;
            color: #777;
        }
        
        .product-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .tag {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
        }
         header{
            background-color: #fcc4d4;
        }
        body {
            background-color: #fcc4d4;
            color: #5a0d24;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
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
        
        /* Product Details Section */
        .product-details-container {
            max-width: 1200px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.1);
        }
        
        @media (max-width: 992px) {
            .product-details-container {
                grid-template-columns: 1fr;
            }
        }
        
        .product-image-section {
            text-align: center;
        }
        
        .product-main-image {
            width: 100%;
            max-width: 400px;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .product-thumbnails {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .product-thumbnail.active {
            border-color: #5a0d24;
        }
        
        .product-info-section {
            padding: 20px;
        }
        
        .product-category {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
        }
        
        .product-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #5a0d24;
        }
        
        .product-price {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #5a0d24;
        }
        
        .product-description {
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .stars {
            color: #ffc107;
            margin-right: 10px;
        }
        
        .rating-count {
            color: #777;
        }
        
        /* Product Selection Form Styles */
        .product-selection-container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.1);
        }
        
        .product-price {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
            color: #5a0d24;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin: 25px 0 15px;
            color: #5a0d24;
        }
        
        .options-group {
            margin-bottom: 20px;
        }
        
        .option-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .options-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .option-item {
            background: #f8f8f8;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .option-item.selected {
            background: #5a0d24;
            color: #fcc4d4;
            border-color: #5a0d24;
        }
        
        .divider {
            height: 1px;
            background: #ddd;
            margin: 25px 0;
        }
        
        .message-section textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }
        
        .char-count {
            text-align: right;
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }
        
        .quantity-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }
        
        .quantity-btn {
            background: #5a0d24;
            color: #fcc4d4;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-input {
            width: 60px;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 18px;
        }
        
        .btn-buy {
            background: #5a0d24;
            color: #fcc4d4;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
            margin-top: 20px;
        }
        
        .btn-buy:hover {
            background: #3a0818;
        }
        
        /* Checkout specific styles */
        .checkout-container {
            background-color: #fcc4d4;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: none; /* Hidden by default */
        }
        
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 992px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .form-section, .product-section {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.1);
        }
        
        .form-header, .product-header {
            border-bottom: 2px solid #5a0d24;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            color: #5a0d24;
            font-size: 16px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #5a0d24;
            box-shadow: 0 0 0 2px rgba(90, 13, 36, 0.2);
        }
        
        .radio-group {
            display: flex;
            gap: 20px;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-buy-now {
            background-color: #5a0d24;
            color: #fcc4d4;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .btn-buy-now:hover {
            background-color: #3a0818;
        }
        
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .error {
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .required-field::after {
            content: " *";
            color: #ff0000;
        }
        
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
        
        /* New styles for redesigned form */
        .order-summary {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .order-summary-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #5a0d24;
        }
        
        .order-summary-price {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            color: #5a0d24;
        }
        
        .section-divider {
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }
        
        .contact-section, .delivery-section {
            margin-bottom: 25px;
        }
        
        .section-title-form {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #5a0d24;
        }
        
        .sign-in-link {
            color: #5a0d24;
            text-decoration: underline;
            font-weight: bold;
        }
        
        .whatsapp-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .country-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .country-flag {
            width: 24px;
            height: 16px;
            border-radius: 2px;
        }
        
        .name-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 576px) {
            .name-fields {
                grid-template-columns: 1fr;
            }
        }
        
        .address-line {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .city-state-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 576px) {
            .city-state-fields {
                grid-template-columns: 1fr;
            }
        }
        
        .save-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .payment-section {
            margin-top: 30px;
        }
        
        .payment-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #5a0d24;
        }
    </style>
</head>
<body>
    <?php include('headers/mainnav.php'); ?>
    <a href="https://wa.me/+919353383242" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <?php
    include("connection.php");
    // Get product ID from URL parameter
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 15; // Default to product ID 15 if not specified
    
    // Fetch product details
    $product = null;
    $product_images = [];
    
    if ($product_id) {
        // Get product info
        $sql = "SELECT p.*, c.category 
                FROM tbl_products p 
                LEFT JOIN tbl_category c ON p.category_id = c.category_id 
                WHERE p.productmaster_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            
            // Get product images
            $sql_images = "SELECT * FROM tbl_product_images WHERE product_id = ?";
            $stmt_images = $conn->prepare($sql_images);
            $stmt_images->bind_param("i", $product_id);
            $stmt_images->execute();
            $result_images = $stmt_images->get_result();
            
            while ($row = $result_images->fetch_assoc()) {
                $product_images[] = $row;
            }
        }
        $stmt->close();
    }
    
    // Fetch available flavors
    $flavors = [];
    $sql_flavors = "SELECT * FROM tbl_flavors WHERE is_active = 1";
    $result_flavors = $conn->query($sql_flavors);
    
    if ($result_flavors->num_rows > 0) {
        while ($row = $result_flavors->fetch_assoc()) {
            $flavors[] = $row;
        }
    }
    
    // Close connection
    $conn->close();
    ?>
    
    <!-- Product Details Section -->
    <div class="product-details-container">
        <div class="product-image-section">
            <?php if ($product && !empty($product['image'])): ?>
                <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-main-image" id="mainProductImage">
                
                <?php if (!empty($product_images)): ?>
                    <div class="product-thumbnails">
                        <?php foreach ($product_images as $index => $image): ?>
                            <img src="<?php echo $image['image_path']; ?>" alt="Product Image <?php echo $index+1; ?>" class="product-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeMainImage('<?php echo $image['image_path']; ?>', this)">
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- If no additional images, just show the main image as thumbnail -->
                    <div class="product-thumbnails">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-thumbnail active" onclick="changeMainImage('<?php echo $product['image']; ?>', this)">
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <h4>Product image not available</h4>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="product-info-section">
            <?php if ($product): ?>
                <div class="product-category"><?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></div>
                <h1 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <div class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></div>
                
                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="rating-count">(4.5/5 from 23 reviews)</span>
                </div>
                
                <div class="product-description">
                    <?php echo htmlspecialchars($product['description']); ?>
                </div>
                
                <!-- Quick action buttons -->
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button class="btn-buy" onclick="scrollToSelection()">Customize & Order</button>
                    
                        <i class="far fa-heart"></i></button>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <h4>Product not found</h4>
                    <p>The product you're looking for doesn't exist or has been removed.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Product Selection Form -->
    <div class="product-selection-container" id="selectionForm">
        <div class="product-price" id="dynamicPrice">Rs. <?php echo $product ? number_format($product['price'], 2) : '0.00'; ?></div>
        
        <div class="section-title">Sizes</div>
        <div class="options-group">
            <div class="options-list">
                <div class="option-item selected" data-price-multiplier="1">Small (600gm)</div>
                <div class="option-item" data-price-multiplier="1.5">Medium (1kg)</div>
                <div class="option-item" data-price-multiplier="2.5">Large (2kg)</div>
            </div>
        </div>
        
        <div class="section-title">Options</div>
        <div class="options-group">
            <div class="options-list">
                <div class="option-item selected">Eggless</div>
                <div class="option-item">With Egg</div>
            </div>
        </div>
        
        <div class="section-title">Flavors</div>
        <div class="options-group">
            <div class="options-list">
                <?php if (!empty($flavors)): ?>
                    <?php foreach ($flavors as $index => $flavor): ?>
                        <div class="option-item <?php echo $index === 0 ? 'selected' : ''; ?>"><?php echo htmlspecialchars($flavor['flavor_name']); ?></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="option-item selected">Butterscotch</div>
                    <div class="option-item">Chocolate</div>
                    <div class="option-item">Vanilla</div>
                    <div class="option-item">Red Velvet</div>
                    <div class="option-item">Strawberry</div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="message-section">
            <div class="section-title">Message Or Special Notes (Max: 50 Characters)</div>
            <textarea id="special-notes" placeholder="E.g. Happy Birthday!!!"></textarea>
            <div class="char-count"><span id="char-count">0</span>/50</div>
        </div>
        
        <div class="divider"></div>
        
        <div class="quantity-section">
            <div class="section-title">Quantity</div>
            <button class="quantity-btn" id="decreaseQtySelect">-</button>
            <input type="number" class="quantity-input" id="quantitySelect" value="1" min="1" max="10">
            <button class="quantity-btn" id="increaseQtySelect">+</button>
        </div>
        
        <button class="btn-buy" id="btn-buy">Buy</button>
    </div>
    
    <!-- Order Form - Hidden by default -->
    <div class="checkout-container section-padding" id="checkoutContainer">
        <div class="checkout-grid">
            <!-- Product Summary -->
            <div class="product-section">
                <div class="product-header">
                    <h2 class="text-light-black">Your Order</h2>
                </div>
                
                <?php if ($product): ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image" onerror="this.src='default-cake.jpg'">
                    
                    <h3 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    
                    <div class="product-price">
                        Rs. <span id="unitPrice"><?php echo number_format($product['price'], 2); ?></span> INR
                    </div>
                    
                    <div class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </div>
                    
                    <div class="quantity-selector">
                        <label class="form-label">Quantity:</label>
                        <button type="button" class="quantity-btn" id="decreaseQty">-</button>
                        <input type="number" class="quantity-input" id="quantity" name="quantity" value="1" min="1" max="10">
                        <button type="button" class="quantity-btn" id="increaseQty">+</button>
                    </div>
                    
                    <div class="product-price" id="totalPrice">
                        Total: Rs. <span id="totalAmount"><?php echo number_format($product['price'], 2); ?></span> INR
                    </div>
                    
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <h4>Product not found</h4>
                        <p>Please select a valid product to proceed with your order.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Order Form - Redesigned -->
            <div class="form-section">
                <div class="order-summary">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="order-summary-header">Order summary</div>
                        <div class="order-summary-price" id="orderSummaryPrice">Rs. <?php echo $product ? number_format($product['price'], 2) : '0.00'; ?></div>
                    </div>
                </div>
                
                <?php if (isset($error_message)): ?>
                    <div class="error-message" id="errorMessage">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form id="orderForm" method="POST" action="process_order.php">
                    <input type="hidden" name="product_id" value="<?php echo $product ? $product['productmaster_id'] : ''; ?>">
                    <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $product ? $product['price'] : ''; ?>">
                    <input type="hidden" name="selected_size" id="selectedSize" value="Small (600gm)">
                    <input type="hidden" name="selected_option" id="selectedOption" value="Eggless">
                    <input type="hidden" name="selected_flavor" id="selectedFlavor" value="<?php echo !empty($flavors) ? $flavors[0]['flavor_name'] : 'Butterscotch'; ?>">
                    <input type="hidden" name="special_notes" id="specialNotes" value="">
                    
                    <!-- Contact Section -->
                    <div class="contact-section">
                        <div class="section-title-form">Contact</div>
                        <div style="margin-bottom: 15px;">
                            <a href="#" class="sign-in-link">Sign in</a>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" class="form-control" id="email_phone" name="email_phone" placeholder="Email or mobile phone number" value="<?php echo isset($_POST['email_phone']) ? htmlspecialchars($_POST['email_phone']) : ''; ?>" required>
                            <div class="error" id="email_phone_error"></div>
                        </div>
                        
                        <div class="whatsapp-checkbox">
                            <input type="checkbox" id="whatsapp_updates" name="whatsapp_updates" <?php echo (isset($_POST['whatsapp_updates']) && $_POST['whatsapp_updates'] == 'on') ? 'checked' : ''; ?>>
                            <label for="whatsapp_updates">Get order updates on WhatsApp</label>
                        </div>
                    </div>
                    
                    <div class="section-divider"></div>
                    
                    <!-- Delivery Section -->
                    <div class="delivery-section">
                        <div class="section-title-form">Delivery</div>
                        
                        <div class="country-selector">
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NCA0OCI+PHBhdGggZmlsbD0iIzM4OTgwNiIgZD0iTTAgMzJoNjR2MTZIMHoiLz48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMCAwaDY0djMySDB6Ii8+PHBhdGggZmlsbD0iI2ZmOGMwMCIgZD0iTTAgMGg2NHYxNkgweiIvPjxjaXJjbGUgZmlsbD0iIzAwODVjMyIgY3g9IjMyIiBjeT0iMjQiIHI9IjkuNiIvPjxjaXJjbGUgZmlsbD0iI2ZmZiIgY3g9IjMyIiBjeT0iMjQiIHI9IjcuMiIvPjxwYXRoIGZpbGw9IiMwMDg1YzMiIGQ9Ik0zMiAyNGw1LjkgNC41LTIuMi02LjloLTcuNGwtMi4yIDYuOXoiLz48cGF0aCBZmlsbD0iIzAwODVjMyIgZD0iTTMyIDI0bC01LjkgNC41IDIuMi02LjloNy40TDM3LjkgMjguNXoiLz48L3N2Zz4=" alt="India Flag" class="country-flag">
                            <span>India</span>
                        </div>
                        
                        <div class="name-fields">
                            <div class="form-group">
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                                <div class="error" id="first_name_error"></div>
                            </div>
                            
                            <div class="form-group">
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                                <div class="error" id="last_name_error"></div>
                            </div>
                        </div>
                        
                        <div class="address-line">
                            <div class="form-group">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
                                <div class="error" id="address_error"></div>
                            </div>
                            
                            <div class="form-group">
                                <input type="text" class="form-control" id="apartment" name="apartment" placeholder="Apartment, suite, etc. (optional)" value="<?php echo isset($_POST['apartment']) ? htmlspecialchars($_POST['apartment']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="city-state-fields">
                            <div class="form-group">
                                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
                                <div class="error" id="city_error"></div>
                            </div>
                            
                            <div class="form-group">
                                <select class="form-control" id="state" name="state" required>
                                    <option value="">Select State</option>
                                    <option value="Karnataka" <?php echo (isset($_POST['state']) && $_POST['state'] == 'Karnataka') ? 'selected' : ''; ?>>Karnataka</option>
                                    <!-- Add other Indian states as needed -->
                                </select>
                                <div class="error" id="state_error"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" class="form-control" id="postcode" name="postcode" placeholder="PIN code" value="<?php echo isset($_POST['postcode']) ? htmlspecialchars($_POST['postcode']) : ''; ?>" required>
                            <div class="error" id="postcode_error"></div>
                        </div>
                        
                        <div class="form-group">
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone number for order updates" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                            <div class="error" id="phone_error"></div>
                        </div>
                        
                        <div class="save-info">
                            <input type="checkbox" id="save_info" name="save_info" <?php echo (isset($_POST['save_info']) && $_POST['save_info'] == 'on') ? 'checked' : ''; ?>>
                            <label for="save_info">Save this information for next time</label>
                        </div>
                    </div>
                    
                    <div class="section-divider"></div>
                    
                    <!-- Shipping Method Section -->
                    <div class="shipping-section">
                        <div class="section-title-form">Shipping method</div>
                        <p>Enter your shipping address to view available shipping methods.</p>
                    </div>
                    
                    <div class="section-divider"></div>
                    
                    <!-- Payment Section -->
                    <div class="payment-section">
                        <div class="payment-title">Payment</div>
                        <p>All transactions are secure and encrypted.</p>
                        <!-- Payment options would go here -->
                    </div>
                    
                    <button type="submit" class="btn-buy-now">Proceed to Payment</button>
                </form>
            </div>
        </div>
    </div>
    
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
    
    <script>
        // Product Details Functions
        function changeMainImage(src, element) {
            document.getElementById('mainProductImage').src = src;
            
            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.product-thumbnail');
            thumbnails.forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            element.classList.add('active');
        }
        
        function scrollToSelection() {
            document.getElementById('selectionForm').scrollIntoView({ behavior: 'smooth' });
        }
        
    
        document.addEventListener('DOMContentLoaded', function() {
            // Product Selection Form Functionality
            const optionItems = document.querySelectorAll('.option-item');
            const specialNotes = document.getElementById('special-notes');
            const charCount = document.getElementById('char-count');
            const quantitySelect = document.getElementById('quantitySelect');
            const decreaseBtnSelect = document.getElementById('decreaseQtySelect');
            const increaseBtnSelect = document.getElementById('increaseQtySelect');
            const btnBuy = document.getElementById('btn-buy');
            const checkoutContainer = document.getElementById('checkoutContainer');
            const dynamicPrice = document.getElementById('dynamicPrice');
            
            // Base price from database
            const basePrice = <?php echo $product ? $product['price'] : 1400; ?>;
            
            // Update price based on selections
            function updatePrice() {
                // Get selected size multiplier
                const selectedSize = document.querySelector('.options-group:nth-child(2) .option-item.selected');
                const sizeMultiplier = selectedSize ? parseFloat(selectedSize.getAttribute('data-price-multiplier')) : 1;
                
                // Get quantity
                const quantity = parseInt(quantitySelect.value);
                
                // Calculate new price
                const newPrice = basePrice * sizeMultiplier * quantity;
                
                // Update displayed price
                dynamicPrice.textContent = 'Rs. ' + newPrice.toFixed(2);
            }
            
            // Option selection
            optionItems.forEach(item => {
                item.addEventListener('click', function() {
                    const parent = this.parentElement;
                    const siblings = parent.querySelectorAll('.option-item');
                    
                    siblings.forEach(sibling => {
                        sibling.classList.remove('selected');
                    });
                    
                    this.classList.add('selected');
                    
                    // Update price if it's a size option
                    if (this.hasAttribute('data-price-multiplier')) {
                        updatePrice();
                    }
                });
            });
            
            // Character count for special notes
            specialNotes.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                
                if (count > 50) {
                    this.value = this.value.substring(0, 50);
                    charCount.textContent = 50;
                    charCount.style.color = '#ff0000';
                } else {
                    charCount.style.color = '#777';
                }
            });
            
            // Quantity controls for selection form
            decreaseBtnSelect.addEventListener('click', function() {
                let quantity = parseInt(quantitySelect.value);
                if (quantity > 1) {
                    quantitySelect.value = quantity - 1;
                    updatePrice();
                }
            });
            
            increaseBtnSelect.addEventListener('click', function() {
                let quantity = parseInt(quantitySelect.value);
                if (quantity < 10) {
                    quantitySelect.value = quantity + 1;
                    updatePrice();
                }
            });
            
            quantitySelect.addEventListener('change', function() {
                let quantity = parseInt(quantitySelect.value);
                if (isNaN(quantity) || quantity < 1) {
                    quantitySelect.value = 1;
                } else if (quantity > 10) {
                    quantitySelect.value = 10;
                }
                updatePrice();
            });
            
            // Buy button click - show order form
            btnBuy.addEventListener('click', function() {
                // Get selected options
                const selectedSize = document.querySelector('.options-group:nth-child(2) .option-item.selected').textContent;
                const selectedOption = document.querySelector('.options-group:nth-child(4) .option-item.selected').textContent;
                const selectedFlavor = document.querySelector('.options-group:nth-child(6) .option-item.selected').textContent;
                const specialNotesText = specialNotes.value;
                const quantity = quantitySelect.value;
                
                // Set values in order form
                document.getElementById('selectedSize').value = selectedSize;
                document.getElementById('selectedOption').value = selectedOption;
                document.getElementById('selectedFlavor').value = selectedFlavor;
                document.getElementById('specialNotes').value = specialNotesText;
                document.getElementById('quantity').value = quantity;
                
                // Calculate and set total price
                const selectedSizeElement = document.querySelector('.options-group:nth-child(2) .option-item.selected');
                const sizeMultiplier = selectedSizeElement ? parseFloat(selectedSizeElement.getAttribute('data-price-multiplier')) : 1;
                const totalPrice = basePrice * sizeMultiplier * parseInt(quantity);
                
                document.getElementById('totalAmount').textContent = totalPrice.toFixed(2);
                document.getElementById('total_amount').value = totalPrice;
                document.getElementById('orderSummaryPrice').textContent = 'Rs. ' + totalPrice.toFixed(2);
                
                // Hide product selection and show order form
                document.querySelector('.product-selection-container').style.display = 'none';
                checkoutContainer.style.display = 'block';
                
                // Scroll to order form
                checkoutContainer.scrollIntoView({ behavior: 'smooth' });
            });
            
            // Order Form Functionality
            const quantityInput = document.getElementById('quantity');
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const totalPriceElement = document.getElementById('totalPrice');
            const totalAmountInput = document.getElementById('total_amount');
            
            // Update total price in order form
            function updateOrderFormPrice() {
                const quantity = parseInt(quantityInput.value);
                const selectedSizeElement = document.querySelector('.options-group:nth-child(2) .option-item.selected');
                const sizeMultiplier = selectedSizeElement ? parseFloat(selectedSizeElement.getAttribute('data-price-multiplier')) : 1;
                const total = basePrice * sizeMultiplier * quantity;
                
                document.getElementById('totalAmount').textContent = total.toFixed(2);
                totalAmountInput.value = total;
                document.getElementById('orderSummaryPrice').textContent = 'Rs. ' + total.toFixed(2);
            }
            
            // Event listeners for quantity buttons in order form
            decreaseBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                if (quantity > 1) {
                    quantityInput.value = quantity - 1;
                    updateOrderFormPrice();
                }
            });
            
            increaseBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                if (quantity < 10) {
                    quantityInput.value = quantity + 1;
                    updateOrderFormPrice();
                }
            });
            
            quantityInput.addEventListener('change', function() {
                let quantity = parseInt(quantityInput.value);
                if (isNaN(quantity) || quantity < 1) {
                    quantityInput.value = 1;
                } else if (quantity > 10) {
                    quantityInput.value = 10;
                }
                updateOrderFormPrice();
            });
            
            // Form validation
            const orderForm = document.getElementById('orderForm');
            
            orderForm.addEventListener('submit', function(event) {
                let isValid = true;
                
                // Validate email/phone
                const emailPhoneInput = document.getElementById('email_phone');
                const emailPhoneError = document.getElementById('email_phone_error');
                if (!emailPhoneInput.value.trim()) {
                    emailPhoneError.textContent = 'Please enter your email or phone number';
                    isValid = false;
                } else {
                    emailPhoneError.textContent = '';
                }
                
                // Validate first name
                const firstNameInput = document.getElementById('first_name');
                const firstNameError = document.getElementById('first_name_error');
                if (!firstNameInput.value.trim()) {
                    firstNameError.textContent = 'Please enter your first name';
                    isValid = false;
                } else {
                    firstNameError.textContent = '';
                }
                
                // Validate last name
                const lastNameInput = document.getElementById('last_name');
                const lastNameError = document.getElementById('last_name_error');
                if (!lastNameInput.value.trim()) {
                    lastNameError.textContent = 'Please enter your last name';
                    isValid = false;
                } else {
                    lastNameError.textContent = '';
                }
                
                // Validate address
                const addressInput = document.getElementById('address');
                const addressError = document.getElementById('address_error');
                if (!addressInput.value.trim()) {
                    addressError.textContent = 'Please enter your delivery address';
                    isValid = false;
                } else {
                    addressError.textContent = '';
                }
                
                // Validate city
                const cityInput = document.getElementById('city');
                const cityError = document.getElementById('city_error');
                if (!cityInput.value.trim()) {
                    cityError.textContent = 'Please enter your city';
                    isValid = false;
                } else {
                    cityError.textContent = '';
                }
                
                // Validate state
                const stateInput = document.getElementById('state');
                const stateError = document.getElementById('state_error');
                if (!stateInput.value) {
                    stateError.textContent = 'Please select your state';
                    isValid = false;
                } else {
                    stateError.textContent = '';
                }
                
                // Validate postcode
                const postcodeInput = document.getElementById('postcode');
                const postcodeError = document.getElementById('postcode_error');
                const postcodePattern = /^[0-9]{4,10}$/;
                if (!postcodeInput.value.trim()) {
                    postcodeError.textContent = 'Please enter your PIN code';
                    isValid = false;
                } else if (!postcodePattern.test(postcodeInput.value)) {
                    postcodeError.textContent = 'Please enter a valid PIN code';
                    isValid = false;
                } else {
                    postcodeError.textContent = '';
                }
                
                // Validate phone
                const phoneInput = document.getElementById('phone');
                const phoneError = document.getElementById('phone_error');
                const phonePattern = /^(\+\d{1,3}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/;
                if (!phoneInput.value.trim()) {
                    phoneError.textContent = 'Please enter your phone number';
                    isValid = false;
                } else if (!phonePattern.test(phoneInput.value)) {
                    phoneError.textContent = 'Please enter a valid phone number';
                    isValid = false;
                } else {
                    phoneError.textContent = '';
                }
                
                if (!isValid) {
                    event.preventDefault();
                    // Show error message
                    const errorMessage = document.getElementById('errorMessage');
                    if (errorMessage) {
                        errorMessage.style.display = 'block';
                        errorMessage.textContent = 'Please correct the errors in the form.';
                    } else {
                        // Create error message if it doesn't exist
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.id = 'errorMessage';
                        errorDiv.textContent = 'Please correct the errors in the form.';
                        orderForm.parentNode.insertBefore(errorDiv, orderForm);
                    }
                }
            });
        });
    </script>
</body>
</html>