<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = getConnection();
$ma_sv = $_SESSION['ma_sv'];

$sql = "DELETE FROM dang_ky WHERE ma_sv = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ma_sv);
$stmt->execute();

$conn->close();
header("Location: gio_hang.php");
?>