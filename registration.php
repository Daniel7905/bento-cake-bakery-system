<?php
session_start();
include("connection.php");

// Redirect to admin home if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_home.php");
    exit();
}

// Initialize variables
$error = '';
$success = '';
$name = '';
$email = '';
$role = 'staff'; // Default role

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : 'staff';

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        // Check if email already exists
        $query = "SELECT admin_id FROM tbl_admin_users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email already registered";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin
            $insert_query = "INSERT INTO tbl_admin_users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Admin account created successfully!";
                // Clear form
                $name = $email = '';
                $role = 'staff';
            } else {
                $error = "Error creating account: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Bento Cake Bangalore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h2 {
            color: #e83e8c;
        }
        .form-control:focus {
            border-color: #e83e8c;
            box-shadow: 0 0 0 0.25rem rgba(232, 62, 140, 0.25);
        }
        .btn-register {
            background-color: #e83e8c;
            border-color: #e83e8c;
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }
        .btn-register:hover {
            background-color: #d62e7c;
            border-color: #d62e7c;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            background: #eee;
        }
        .password-strength span {
            display: block;
            height: 100%;
            transition: width 0.3s, background 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <h2>Bento Cake Bangalore</h2>
                <p class="text-muted">Admin Registration</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="password-strength">
                        <span id="password-strength-bar"></span>
                    </div>
                    <small class="text-muted">Minimum 8 characters</small>
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="staff" <?php echo $role === 'staff' ? 'selected' : ''; ?>>Staff</option>
                            <option value="super_admin" <?php echo $role === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="role" value="staff">
                <?php endif; ?>
                
                <button type="submit" class="btn btn-primary btn-register">Register Admin</button>
                
                <div class="mt-3 text-center">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            let strength = 0;
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            const width = (strength / 5) * 100;
            let color;
            
            if (strength <= 1) color = '#dc3545'; // Red
            else if (strength <= 3) color = '#ffc107'; // Yellow
            else color = '#28a745'; // Green
            
            strengthBar.style.width = width + '%';
            strengthBar.style.backgroundColor = color;
        });
    </script>
</body>
</html>