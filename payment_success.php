<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Bento Cakes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #ff9e9e;
            --accent-color: #ff5252;
            --light-color: #fff5f5;
            --dark-color: #4a3f35;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .success-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .success-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 1rem;
            animation: bounce 1s;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }
        
        .cake-decoration {
            height: 20px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--primary-color));
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        
        .order-details {
            background-color: var(--light-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            opacity: 0.7;
            border-radius: 50%;
            animation: confetti-fall 5s linear infinite;
            z-index: -1;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .value-box {
            background-color: white;
            border-radius: 8px;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    require_once 'connection.php';

    // Verify payment signature
    $keySecret = 'JxXZmRvSuuLBSGnmVZvJMjfn';

    // Get payment details from URL parameters
    $razorpay_payment_id = isset($_GET['razorpay_payment_id']) ? $_GET['razorpay_payment_id'] : 'N/A';
    $razorpay_order_id = isset($_GET['razorpay_order_id']) ? $_GET['razorpay_order_id'] : 'N/A';
    $razorpay_signature = isset($_GET['razorpay_signature']) ? $_GET['razorpay_signature'] : 'N/A';

    // Generate signature for verification
    $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $keySecret);

    // Initialize variables
    $payment_success = false;
    $order_id = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : 'N/A';

    if ($generated_signature == $razorpay_signature) {
        // Payment is successful
        $payment_success = true;
        
        // Update order status in database
        if (isset($_SESSION['order_id'])) {
            $sql = "UPDATE tbl_orders SET order_status = 'paid', payment_id = ?, payment_date = NOW() WHERE order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $razorpay_payment_id, $order_id);
            $stmt->execute();
        }
        
        // Clear session data
        unset($_SESSION['order_data']);
        unset($_SESSION['razorpay_order_id']);
    }
    ?>
    
    <div class="container my-5">
        <div class="success-container">
            <?php if ($payment_success): ?>
            <div class="success-header">
                <i class="fas fa-check-circle success-icon"></i>
                <h1 class="text-success">Payment Successful!</h1>
                <p class="lead">Thank you for your order at Bento Cakes</p>
            </div>
            
            <div class="cake-decoration"></div>
            
            <div class="text-center mb-4">
                <p>Your payment has been processed successfully. Your delicious cake is being prepared!</p>
            </div>
            
            <div class="order-details">
                <h3 class="mb-3"><i class="fas fa-receipt me-2"></i>Order Details</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p><strong>Order ID:</strong></p>
                        <div class="value-box">#ORD-<?php echo str_pad($order_id, 4, '0', STR_PAD_LEFT); ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p><strong>Payment ID:</strong></p>
                        <div class="value-box"><?php echo htmlspecialchars($razorpay_payment_id); ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p><strong>Razorpay Order ID:</strong></p>
                        <div class="value-box"><?php echo htmlspecialchars($razorpay_order_id); ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p><strong>Payment Date:</strong></p>
                        <div class="value-box"><?php echo date('F j, Y, g:i a'); ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>Status:</strong></p>
                        <span class="badge bg-success fs-6">Paid</span>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p>You will receive an email confirmation shortly with your order details.</p>
            </div>
            
            <?php else: ?>
            
            <div class="success-header">
                <i class="fas fa-exclamation-circle success-icon text-danger"></i>
                <h1 class="text-danger">Payment Verification Failed</h1>
                <p class="lead">There was an issue verifying your payment</p>
            </div>
            
            <div class="cake-decoration"></div>
            
            <div class="alert alert-warning">
                <p class="mb-0">Please contact our support team with your payment details for assistance.</p>
            </div>
            
            <div class="order-details">
                <h3 class="mb-3"><i class="fas fa-info-circle me-2"></i>Payment Details</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p><strong>Payment ID:</strong></p>
                        <div class="value-box"><?php echo htmlspecialchars($razorpay_payment_id); ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p><strong>Razorpay Order ID:</strong></p>
                        <div class="value-box"><?php echo htmlspecialchars($razorpay_order_id); ?></div>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>
            
            <div class="d-flex justify-content-center mt-4 gap-3 flex-wrap">
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Return to Home
                </a>
                <a href="orders.php" class="btn btn-outline-primary">
                    <i class="fas fa-list-alt me-2"></i>View Orders
                </a>
                <a href="contact.php" class="btn btn-outline-secondary">
                    <i class="fas fa-headset me-2"></i>Contact Support
                </a>
            </div>
            
            <div class="footer mt-4">
                <p>Need help? <a href="contact.php">Contact our support team</a></p>
                <p class="small">© 2025 Bento Cakes. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Create confetti effect for successful payment
        <?php if ($payment_success): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const colors = ['#ff6b6b', '#ff9e9e', '#ff5252', '#ffb6b6', '#ff8585'];
            const container = document.body;
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                confetti.style.animationDelay = (Math.random() * 2) + 's';
                container.appendChild(confetti);
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>