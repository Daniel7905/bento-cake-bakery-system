<?php
include("connection.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM tbl_feedback WHERE feedback_id = '$id'";
    
    if(mysqli_query($conn, $query)) {
        header("Location: admin_home.php?message=Feedback+deleted+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+deleting+feedback&type=error");
    }
} else {
    header("Location: admin_home.php");
}

mysqli_close($conn);
?>