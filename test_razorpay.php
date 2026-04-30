<?php
// Include Razorpay configuration
require_once 'razorpay_config.php';
require_once 'razorpay-php/Razorpay.php';

use Razorpay\Api\Api;

echo "<h2>Testing Razorpay Credentials</h2>";

try {
    // Initialize Razorpay API with your credentials
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
    
    echo "<p>✓ Successfully initialized Razorpay API</p>";
    
    // Test the connection by creating a test payment link
    $paymentLink = $api->paymentLink->create([
        'amount' => 100, // 1 INR (100 paise)
        'currency' => 'INR',
        'accept_partial' => false,
        'description' => 'Test payment link for credentials verification',
        'customer' => [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'contact' => '9999999999'
        ],
        'notify' => [
            'sms' => false,
            'email' => false
        ],
        'reminder_enable' => false,
        'notes' => [
            'purpose' => 'Testing Razorpay credentials'
        ]
    ]);
    
    echo "<p>✓ Connection successful! Test payment link created.</p>";
    echo "<p>Payment Link ID: " . $paymentLink->id . "</p>";
    echo "<p>Payment Link URL: <a href='" . $paymentLink->short_url . "' target='_blank'>" . $paymentLink->short_url . "</a></p>";
    
    echo "<p style='color: green; font-weight: bold;'>✓ Your Razorpay credentials are working correctly!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>✗ Error: " . $e->getMessage() . "</p>";
    
    // Additional error information
    if (strpos($e->getMessage(), 'Authentication failed') !== false) {
        echo "<p>This usually means your Key ID or Key Secret is incorrect.</p>";
        echo "<p>Please verify your credentials in the Razorpay dashboard.</p>";
    }
}

echo "<hr>";
echo "<h3>Credentials Used:</h3>";
echo "<p>Key ID: " . RAZORPAY_KEY_ID . "</p>";
echo "<p>Key Secret: " . substr(RAZORPAY_KEY_SECRET, 0, 5) . "..." . "</p>"; // Show only first 5 chars for security

// Alternative test: Check if we can retrieve payments (empty if no payments yet)
try {
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
    $payments = $api->payment->all();
    echo "<p>✓ Payments API accessible. Found " . count($payments->items) . " payments in your account.</p>";
} catch (Exception $e) {
    echo "<p>Payments API test failed: " . $e->getMessage() . "</p>";
}
?>