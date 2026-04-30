<?php
session_start();
include("connection.php");
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['contact_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'customer';

    $sql = "SELECT * FROM tbl_login WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Email Exists',
            'text' => 'User with this email address already exists. Please use another email address!'
        ];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
         $sql = "INSERT INTO tbl_login(email,password,role) VALUES('$email','$password','$role')";
        if (mysqli_query($conn, $sql)) {
             $loginid = mysqli_insert_id($conn);
            $insert_user = "INSERT INTO tbl_customer (login_id, name,contact_no)VALUES ('$loginid', '$name', '$phone')";
            if (mysqli_query($conn, $insert_user)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'title' => 'Registration Successful',
                    'text' => 'Your account has been created successfully!'
                ];
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'title' => 'Registration Failed',
                    'text' => 'There was an error creating your account. Please try again later.'
                ];
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'title' => 'Registration Failed',
                'text' => 'There was an error creating your account. Please try again later.'
            ];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
// Display flash messages if they exist
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "' . $flash['type'] . '",
                    title: "' . $flash['title'] . '",
                    text: "' . $flash['text'] . '",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                })';

    if (isset($flash['redirect'])) {
        echo '.then((result) => {
                    if (result.isConfirmed) {
                        window.location.href="' . $flash['redirect'] . '";
                    }
                })';
    }

    echo '});
          </script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content="#">
    <meta name="description" content="#">
    <title>Bento cake | Register</title>
    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="#">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="#">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="#">
    <link rel="apple-touch-icon-precomposed" href="#">
    <link rel="shortcut icon" href="#">
    <!-- Bootstrap -->
    <link href="mainstyle/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fontawesome -->
    <link href="mainstyle/css/font-awesome.css" rel="stylesheet">
    <!-- Flaticons -->
    <link href="mainstyle/css/font/flaticon.css" rel="stylesheet">
    <!-- Swiper Slider -->
    <link href="mainstyle/css/swiper.min.css" rel="stylesheet">
    <!-- Range Slider -->
    <link href="mainstyle/css/ion.rangeSlider.min.css" rel="stylesheet">
    <!-- magnific popup -->
    <link href="mainstyle/css/magnific-popup.css" rel="stylesheet">
    <!-- Nice Select -->
    <link href="mainstyle/css/nice-select.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="mainstyle/css/style.css" rel="stylesheet">
    <!-- Custom Responsive -->
    <link href="mainstyle/css/responsive.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" rel="stylesheet">
    <style>
        .text-danger {
            color: #ff4d4d;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        .form-control.error {
            border-color: #ff4d4d !important;
        }
    </style>
</head>

<body>
    <div class="inner-wrapper">
        <div class="container-fluid no-padding">
            <div class="row no-gutters overflow-auto">
                <div class="col-md-6">
                    <div class="main-banner">
                        <img src="mainstyle/img/banner/banner-1.jpg" class="img-fluid full-width main-img" alt="banner">
                        <div class="overlay-2 main-padding">
                            <img src="mainstyle/img/logo-2.jpg" class="img-fluid" alt="logo">
                        </div>
                        <img src="mainstyle/img/banner/burger.png" class="footer-img" alt="footer-img">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-2 user-page main-padding">
                        <div class="login-sec">
                            <div class="login-box">
                                <form id="registrationForm" method="post" action="#">

                                    <h4 class="text-light-black fw-600">Create your account</h4>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="text-light-white fs-14">Full name</label>
                                                <input type="text" name="name" class="form-control form-control-submit" placeholder="Full Name" required>
                                                <div class="error-message text-danger"></div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="text-light-white fs-14">Contact Number</label>
                                                <input type="tel" name="contact_no" class="form-control form-control-submit" placeholder="Contact Number" required>
                                                <div class="error-message text-danger"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-light-white fs-14">Email</label>
                                                <input type="email" name="email" class="form-control form-control-submit" placeholder="Email I'd" required>
                                                <div class="error-message text-danger"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-light-white fs-14">Password (8 character minimum)</label>
                                                <input type="password" id="password-field" name="password" class="form-control form-control-submit" placeholder="Password" required>
                                                <div data-name="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></div>
                                                <div class="error-message text-danger"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-light-white fs-14">Confirm Password</label>
                                                <input type="password" id="confirm-password-field" name="confirm_password" class="form-control form-control-submit" placeholder="Confirm Password" required>
                                                <div data-name="#confirm-password-field" class="fa fa-fw fa-eye field-icon toggle-password"></div>
                                                <div class="error-message text-danger"></div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" name="submit" class="btn-second btn-submit full-width">Create your account</button>
                                            </div>
                                            <span class="text-light-black fs-12 terms">By creating your Munchbox account, you agree to the <a href="#"> Terms of Use </a> and <a href="#"> Privacy Policy.</a></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Final form submission
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault(); // Prevent if validation fails
    }
});

    // Validation functions
    function validateName(input) {
        const name = input.value.trim();
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');
        
        if (name.length < 3) {
            showError(formGroup, errorElement, "Name must be at least 3 characters long");
            return false;
        }

        if (!/^[a-zA-Z\s]+$/.test(name)) {
            showError(formGroup, errorElement, "Name can only contain letters and spaces");
            return false;
        }

        hideError(formGroup, errorElement);
        return true;
    }

    function validatePhone(input) {
        const phone = input.value.trim();
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');

        if (!/^\d{10,15}$/.test(phone)) {
            showError(formGroup, errorElement, "Phone number must be 10-15 digits");
            return false;
        }

        hideError(formGroup, errorElement);
        return true;
    }

    function validateEmail(input) {
        const email = input.value.trim();
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            showError(formGroup, errorElement, "Please enter a valid email address");
            return false;
        }

        hideError(formGroup, errorElement);
        return true;
    }

    function validatePassword(input) {
        const password = input.value;
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');

        if (password.length < 8) {
            showError(formGroup, errorElement, "Password must be at least 8 characters long");
            return false;
        }

        hideError(formGroup, errorElement);
        return true;
    }

    function validateConfirmPassword(input) {
        const confirmPassword = input.value;
        const password = document.getElementById('password-field').value;
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');

        if (confirmPassword !== password) {
            showError(formGroup, errorElement, "Passwords do not match");
            return false;
        }

        hideError(formGroup, errorElement);
        return true;
    }

    // Helper functions
    function showError(formGroup, errorElement, message) {
        // Set error message
        errorElement.textContent = message;
        
        // Highlight the input
        const input = formGroup.querySelector('.form-control');
        input.classList.add('error');
    }

    function hideError(formGroup, errorElement) {
        // Remove error message
        errorElement.textContent = '';
        
        // Reset input border
        const input = formGroup.querySelector('.form-control');
        input.classList.remove('error');
    }

    // Form submission validation
    function validateForm() {
        // Validate all fields
        const isNameValid = validateName(document.querySelector('input[name="name"]'));
        const isPhoneValid = validatePhone(document.querySelector('input[name="phone"]'));
        const isEmailValid = validateEmail(document.querySelector('input[name="email"]'));
        const isPasswordValid = validatePassword(document.getElementById('password-field'));
        const isConfirmPasswordValid = validateConfirmPassword(document.getElementById('confirm-password-field'));

        // Return true only if all validations pass
        return isNameValid && isPhoneValid && isEmailValid &&
            isPasswordValid && isConfirmPasswordValid;
    }

    // Add event listeners for real-time validation
    document.querySelector('input[name="name"]').addEventListener('blur', function() {
        validateName(this);
    });

    document.querySelector('input[name="phone"]').addEventListener('blur', function() {
        validatePhone(this);
    });

    document.querySelector('input[name="email"]').addEventListener('blur', function() {
        validateEmail(this);
    });

    document.getElementById('password-field').addEventListener('blur', function() {
        validatePassword(this);
    });

    document.getElementById('confirm-password-field').addEventListener('blur', function() {
        validateConfirmPassword(this);
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const target = this.getAttribute('data-name');
            const input = document.querySelector(target);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    });


    </script>
</body>
</html>