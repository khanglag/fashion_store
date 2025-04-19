<?php

// define('DATABASE_SERVER', 'localhost');
// define('DATABASE_USER','root');
// define('DATABASE_PASSWORD','');
// define('DATABASE_NAME','php_project');

$conn = mysqli_connect("localhost", "root", '', "php_project");

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
