<?php
session_start();
require_once 'database.php';

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = getConnection();
$ma_sv = $_SESSION['ma_sv'];
$ma_hoc_phan = $_GET['ma_hoc_phan'];

$sql = "DELETE FROM dang_ky WHERE ma_sv = ? AND ma_hoc_phan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $ma_sv, $ma_hoc_phan);
$stmt->execute();

$conn->close();
header("Location: gio_hang.php");
?>