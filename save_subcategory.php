<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $subcat_name = $_POST['subcat_name'];
    
    $query = "INSERT INTO tbl_subcategory (category_id, subcat_name) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $category_id, $subcat_name);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_home.php?message=Subcategory added successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error adding subcategory&type=error");
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>