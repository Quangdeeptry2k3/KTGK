<?php
session_start();
require_once 'database.php';

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = getConnection();
$ma_sv = $_GET['ma_sv'];
$conn->query("DELETE FROM sinh_vien WHERE ma_sv = '$ma_sv'");
$conn->close();
header("Location: index.php");
?>