<?php
require_once 'config.php';
require_once 'razorpay-php/Razorpay.php';

use Razorpay\Api\Api;

// Razorpay API credentials
$razorpayKeyId = 'rzp_live_RJkLzwcNbbsTA3';
$razorpayKeySecret = 'JxXZmRvSuuLBSGnmVZvJMjfn';

// Initialize Razorpay API
$api = new Api($razorpayKeyId, $razorpayKeySecret);

// Get payment details from POST
$razorpayPaymentId = $_POST['razorpay_payment_id'];
$razorpayOrderId = $_POST['razorpay_order_id'];
$razorpaySignature = $_POST['razorpay_signature'];

// Verify payment signature
$generatedSignature = hash_hmac('sha256', $razorpayOrderId . "|" . $razorpayPaymentId, $razorpayKeySecret);

if ($generatedSignature === $razorpaySignature) {
    // Payment is successful
    // Update order status in database
    $stmt = $conn->prepare("UPDATE tbl_orders SET payment_status = 'completed', status = 'confirmed' WHERE razorpay_order_id = ?");
    $stmt->bind_param("s", $razorpayOrderId);
    $stmt->execute();
    
    // Redirect to success page
    header("Location: order-success.php?order_id=" . $order_id);
    exit();
} else {
    // Payment failed
    // Update order status in database
    $stmt = $conn->prepare("UPDATE tbl_orders SET payment_status = 'failed' WHERE razorpay_order_id = ?");
    $stmt->bind_param("s", $razorpayOrderId);
    $stmt->execute();
    
    // Redirect to failure page
    header("Location: order-failed.php?order_id=" . $order_id);
    exit();
}
?>