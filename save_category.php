<?php
include("connection.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];
    
    // Handle file upload
    $target_dir = "uploads/categories/";
    $target_file = $target_dir . basename($_FILES["category_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["category_image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        header("Location: admin_home.php?message=File is not an image.&type=error");
        exit();
    }
    
    // Check file size (5MB max)
    if ($_FILES["category_image"]["size"] > 5000000) {
        $uploadOk = 0;
        header("Location: admin_home.php?message=Sorry, your file is too large.&type=error");
        exit();
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
        header("Location: admin_home.php?message=Sorry, only JPG, JPEG, PNG & GIF files are allowed.&type=error");
        exit();
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        header("Location: admin_home.php?message=Sorry, your file was not uploaded.&type=error");
        exit();
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now insert into database
            $query = "INSERT INTO tbl_category (category, photo) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            
            if($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $category_name, $target_file);
                $result = mysqli_stmt_execute($stmt);
                
                if($result) {
                    header("Location: admin_home.php?message=Category added successfully&type=success");
                } else {
                    header("Location: admin_home.php.php?message=Error adding category&type=error");
                }
                
                mysqli_stmt_close($stmt);
            } else {
                header("Location: admin_home.php?message=Database error&type=error");
            }
        } else {
            header("Location: admin_home.php?message=Sorry, there was an error uploading your file.&type=error");
        }
    }
} else {
    header("Location: admin_home.php?message=Invalid request&type=error");
}

mysqli_close($conn);
?>