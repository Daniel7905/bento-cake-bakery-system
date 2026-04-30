<?php
$dbhost='localhost';
$dbuser='root';
$dbpass='root';
$db='bento_cakes';// replace it with your database
$conn=mysqli_connect($dbhost,$dbuser,$dbpass,$db);
if(!$conn){
    echo "Error connecting to database: ";
}
?>