<?php
session_start();
include("connection.php");

// Initialize variables
$error = '';
$email = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } else {
        // Prepare SQL to prevent SQL injection
        $query = "SELECT * FROM tbl_admin_users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['admin_id'] = $user['admin_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role']; // Make sure your table has this column
                
                // Update last login
                $update_query = "UPDATE tbl_admin_users SET last_login = NOW() WHERE admin_id = ?";
                $stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($stmt, "i", $user['admin_id']);
                mysqli_stmt_execute($stmt);

                // Redirect to admin home
                header("Location: admin_home.php");
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bento Cake Bangalore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #e83e8c;
        }
        .form-control:focus {
            border-color: #e83e8c;
            box-shadow: 0 0 0 0.25rem rgba(232, 62, 140, 0.25);
        }
        .btn-login {
            background-color: #e83e8c;
            border-color: #e83e8c;
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #d62e7c;
            border-color: #d62e7c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h2>Bento Cake Bangalore</h2>
                <p class="text-muted">Admin Login</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-login">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>