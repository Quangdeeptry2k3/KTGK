<?php
function getConnection() {
    $host = "localhost";
    $username = "root";
    $password = "2003"; // Cập nhật mật khẩu thành 2003
    $database = "quanlysinhvien_db";

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    return $conn;
}
?>