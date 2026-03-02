<?php
$con = mysqli_connect("localhost", "root", "", "lastproject");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>