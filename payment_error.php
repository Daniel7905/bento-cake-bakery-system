<?php
session_start();
$error = isset($_GET['error']) ? $_GET['error'] : 'Unknown error occurred';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fcc4d4;
            color: #5a0d24;
            text-align: center;
            padding: 50px;
        }
        .error-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 5px 15px rgba(90, 13, 36, 0.1);
        }
        .btn {
            background-color: #5a0d24;
            color: #fcc4d4;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h2>Payment Processing Error</h2>
        <p>We encountered an issue while processing your payment:</p>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <p>Please check your payment settings or try again later.</p>
        <a href="checkout.php" class="btn">Return to Checkout</a>
        <a href="index.php" class="btn">Return to Home</a>
    </div>
</body>
</html>