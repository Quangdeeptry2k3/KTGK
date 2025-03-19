<?php
session_start();
require_once 'database.php';

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Kiểm tra ma_sv trong session
if (!isset($_SESSION['ma_sv']) || empty($_SESSION['ma_sv'])) {
    echo "Không tìm thấy mã sinh viên. Vui lòng kiểm tra tài khoản của bạn.";
    exit;
}

$conn = getConnection();
$ma_sv = $_SESSION['ma_sv'];
$ma_hoc_phan = $_GET['ma_hoc_phan'];

// Kiểm tra xem sinh viên đã đăng ký học phần này chưa
$check = $conn->query("SELECT * FROM dang_ky WHERE ma_sv = '$ma_sv' AND ma_hoc_phan = '$ma_hoc_phan'");
if ($check->num_rows > 0) {
    echo "Bạn đã đăng ký học phần này rồi!";
} else {
    $sql = "INSERT INTO dang_ky (ma_sv, ma_hoc_phan) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $ma_sv, $ma_hoc_phan);
    if ($stmt->execute()) {
        header("Location: gio_hang.php");
    } else {
        echo "Đăng ký thất bại!";
    }
}
$conn->close();
?>