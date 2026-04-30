<?php
session_start();
require_once 'connection.php';

// Include Razorpay configuration
require_once 'razorpay_config.php';

// Check if order data exists in session
if (!isset($_SESSION['order_data']) || !isset($_SESSION['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_data = $_SESSION['order_data'];
$order_id = $_SESSION['order_id'];

// Include Razorpay PHP library
require_once 'razorpay-php/Razorpay.php';

use Razorpay\Api\Api;

try {
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
    
    // Convert values to strings as required by Razorpay
    $razorpayOrder = $api->order->create([
        'receipt' => (string)$order_id, // Convert to string
        'amount' => (string)($order_data['total_amount'] * 100), // Convert to string
        'currency' => 'INR',
        'payment_capture' => 1
    ]);
    
    // Store Razorpay order ID in session - CORRECTED
    // Razorpay order object should be accessed as an array
    $razorpay_order_id = $razorpayOrder['id'];
    $_SESSION['razorpay_order_id'] = $razorpay_order_id;
    
    // Update order with Razorpay order ID
    $sql = "UPDATE tbl_orders SET razorpay_order_id = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $razorpay_order_id, $order_id);
    $stmt->execute();
    
} catch (Exception $e) {
    // Handle authentication errors
    $error_message = "Payment gateway error: " . $e->getMessage();
    // Log the error and redirect to error page
    error_log("Razorpay Error: " . $e->getMessage());
    header("Location: payment_error.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Processing Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .payment-processing {
            text-align: center;
            padding: 50px;
            font-family: Arial, sans-serif;
        }
        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #5a0d24;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="payment-processing">
        <h2>Processing Your Payment</h2>
        <div class="loader"></div>
        <p>Please wait while we redirect you to the payment gateway...</p>
    </div>

    <script>
        var options = {
            "key": "<?php echo RAZORPAY_KEY_ID; ?>",
            "amount": "<?php echo (string)($order_data['total_amount'] * 100); ?>",
            "currency": "INR",
            "name": "Bento Cake",
            "description": "Order #<?php echo $order_id; ?>",
            "image": "/your_logo.png",
            "order_id": "<?php echo $razorpay_order_id; ?>",
            "handler": function (response){
                // Redirect to success page after successful payment
                window.location.href = "payment_success.php?razorpay_payment_id=" + response.razorpay_payment_id + "&razorpay_order_id=" + response.razorpay_order_id + "&razorpay_signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "<?php echo $order_data['customer_name']; ?>",
                "email": "customer@example.com",
                "contact": "<?php echo $order_data['contact_no']; ?>"
            },
            "notes": {
                "address": "<?php echo $order_data['address']; ?>",
                "order_id": "<?php echo $order_id; ?>"
            },
            "theme": {
                "color": "#5a0d24"
            }
        };
        
        var rzp1 = new Razorpay(options);
        
        // Open Razorpay payment modal automatically
        rzp1.open();
        
        // Handle payment failure
        rzp1.on('payment.failed', function (response){
            window.location.href = "payment_failed.php?error=" + response.error.code + "&error_description=" + response.error.description;
        });
    </script>
</body>
</html>