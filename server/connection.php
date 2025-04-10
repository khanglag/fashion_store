<?php

define('DATABASE_SERVER', 'localhost');
define('DATABASE_USER','root');
define('DATABASE_PASSWORD','');
define('DATABASE_NAME','php_project');

$conn = mysqli_connect(DATABASE_SERVER, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
