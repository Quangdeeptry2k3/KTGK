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

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Xóa học phần khỏi bảng dang_ky
    $sql = "DELETE FROM dang_ky WHERE ma_sv = ? AND ma_hoc_phan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $ma_sv, $ma_hoc_phan);
    $stmt->execute();

    // Tăng số lượng dự kiến
    $sql_update = "UPDATE hoc_phan SET so_luong_du_kien = so_luong_du_kien + 1 WHERE ma_hoc_phan = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("s", $ma_hoc_phan);
    $stmt_update->execute();

    // Xác nhận giao dịch
    $conn->commit();

    header("Location: gio_hang.php");
    exit;
} catch (Exception $e) {
    // Nếu có lỗi, hủy giao dịch
    $conn->rollback();
    echo "Xóa học phần thất bại: " . $e->getMessage();
}

$conn->close();
?>