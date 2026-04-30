<?php
include("connection.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    
    // Check if a new image was uploaded
    if(!empty($_FILES["category_image"]["name"])) {
        // Handle file upload
        $target_dir = "uploads/categories/";
        $target_file = $target_dir . basename($_FILES["category_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["category_image"]["tmp_name"]);
        if($check === false) {
            header("Location: admin_dashboard.php?message=File is not an image.&type=error");
            exit();
        }
        
        // Check file size (5MB max)
        if ($_FILES["category_image"]["size"] > 5000000) {
            header("Location: admin_dashboard.php?message=Sorry, your file is too large.&type=error");
            exit();
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            header("Location: admin_dashboard.php?message=Sorry, only JPG, JPEG, PNG & GIF files are allowed.&type=error");
            exit();
        }
        
        // Try to upload file
        if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
            // Update with new image
            $query = "UPDATE tbl_category SET category = ?, photo = ? WHERE category_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $category_name, $target_file, $category_id);
        } else {
            header("Location: admin_dashboard.php?message=Sorry, there was an error uploading your file.&type=error");
            exit();
        }
    } else {
        // Update without changing the image
        $query = "UPDATE tbl_category SET category = ? WHERE category_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $category_name, $category_id);
    }
    
    $result = mysqli_stmt_execute($stmt);
    
    if($result) {
        header("Location: admin_home.php?message=Category updated successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error updating category&type=error");
    }
    
    mysqli_stmt_close($stmt);
} else {
    header("Location: admin_home.php.php?message=Invalid request&type=error");
}

mysqli_close($conn);
?>