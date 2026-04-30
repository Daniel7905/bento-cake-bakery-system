<?php
include("connection.php");

if(isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    
    // First, get the photo path to delete the file
    $query = "SELECT photo FROM tbl_category WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $photo_path);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    // Delete the category from database
    $query = "DELETE FROM tbl_category WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    $result = mysqli_stmt_execute($stmt);
    
    if($result) {
        // Delete the image file if it exists
        if(file_exists($photo_path)) {
            unlink($photo_path);
        }
        header("Location: admin_home.php?message=Category deleted successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error deleting category&type=error");
    }
    
    mysqli_stmt_close($stmt);
} else {
    header("Location:admin_home.php?message=Invalid request&type=error");
}

mysqli_close($conn);
?>