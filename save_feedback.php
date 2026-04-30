<?php
include("connection.php");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    $query = "INSERT INTO tbl_feedback (fullname, email, feedback, date) 
              VALUES ('$fullname', '$email', '$feedback', NOW())";
    
    if(mysqli_query($conn, $query)) {
        header("Location: admin_home.php?message=Feedback+added+successfully&type=success");
    } else {
        header("Location: admin_home.php?message=Error+adding+feedback&type=error");
    }
} else {
    header("Location: admin_home.php");
}

mysqli_close($conn);
?>