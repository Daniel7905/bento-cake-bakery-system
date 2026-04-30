<?php
include("connection.php");
session_start();

// Get form data
$branch_name = $_POST['branch_name'];
$city = $_POST['city'];
$district = $_POST['district'];
$pin = $_POST['pin'];
$contact_person = $_POST['contact_person'];
$contact_no = $_POST['contact_no'];
$status = $_POST['status'];
$branch_email = $_POST['email']; // You must collect this in your form
$branch_password = $_POST['password']; // Same here

// Sanitize
$branch_email = mysqli_real_escape_string($conn, $branch_email);
$branch_password = mysqli_real_escape_string($conn, $branch_password);

// Step 1: Insert into tbl_login
$role = 'branch';
$login_stmt = $conn->prepare("INSERT INTO tbl_login (email, password, role) VALUES (?, ?, ?)");
$login_stmt->bind_param("sss", $branch_email, $branch_password, $role);

if ($login_stmt->execute()) {
    $login_id = $conn->insert_id; // get login_id from tbl_login

    // Step 2: Insert into tbl_branch
    $stmt = $conn->prepare("INSERT INTO tbl_branch (login_id, branch_name, city, district, pin, contact_person, contact_no, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $login_id, $branch_name, $city, $district, $pin, $contact_person, $contact_no, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Branch and login created successfully'); window.location.href='admin_home.php';</script>";
    } else {
        echo "Branch creation failed: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "Login creation failed: " . $login_stmt->error;
}

$login_stmt->close();
$conn->close();
?>
