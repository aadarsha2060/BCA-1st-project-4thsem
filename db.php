<?php
$conn = mysqli_connect("localhost", "root", "", "system");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
