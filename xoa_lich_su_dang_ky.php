<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = getConnection();
$id = $_GET['id'];

$sql = "DELETE FROM lich_su_dang_ky WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$conn->close();
header("Location: lich_su_dang_ky.php");
exit;
?>