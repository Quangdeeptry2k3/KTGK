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

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Lấy danh sách học phần đã đăng ký để tăng lại số lượng dự kiến
    $result = $conn->query("SELECT ma_hoc_phan FROM dang_ky WHERE ma_sv = '$ma_sv'");
    while ($row = $result->fetch_assoc()) {
        $ma_hoc_phan = $row['ma_hoc_phan'];
        // Tăng số lượng dự kiến
        $sql_update = "UPDATE hoc_phan SET so_luong_du_kien = so_luong_du_kien + 1 WHERE ma_hoc_phan = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $ma_hoc_phan);
        $stmt_update->execute();
    }

    // Xóa tất cả học phần đã đăng ký của sinh viên
    $sql = "DELETE FROM dang_ky WHERE ma_sv = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_sv);
    $stmt->execute();

    // Xác nhận giao dịch
    $conn->commit();

    // Thêm thông báo thành công
    $_SESSION['message'] = '<p style="color: green; text-align: center;">Xóa hết học phần thành công!</p>';
    header("Location: gio_hang.php");
    exit;
} catch (Exception $e) {
    // Nếu có lỗi, hủy giao dịch
    $conn->rollback();
    echo "Xóa hết học phần thất bại: " . $e->getMessage();
}

$conn->close();
?>