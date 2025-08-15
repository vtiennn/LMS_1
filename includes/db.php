<?php
// Kết nối CSDL MySQL bằng PDO cho hệ thống LMS
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms_sdlc";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}
?>
