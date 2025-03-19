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

// Kiểm tra xem sinh viên có tồn tại không
$check_sv = $conn->query("SELECT * FROM sinh_vien WHERE ma_sv = '$ma_sv'");
if ($check_sv->num_rows == 0) {
    echo "Mã sinh viên không tồn tại trong hệ thống!";
    exit;
}

// Kiểm tra xem học phần có tồn tại không
$check_hp = $conn->query("SELECT * FROM hoc_phan WHERE ma_hoc_phan = '$ma_hoc_phan'");
if ($check_hp->num_rows == 0) {
    echo "Mã học phần không tồn tại trong hệ thống!";
    exit;
}

// Lấy số lượng dự kiến của học phần
$hp_row = $check_hp->fetch_assoc();
$so_luong_du_kien = $hp_row['so_luong_du_kien'];

// Kiểm tra số lượng dự kiến
if ($so_luong_du_kien <= 0) {
    echo '<p style="color: red; text-align: center;">Học phần này đã hết chỗ! Vui lòng chọn học phần khác. <a href="dang_ky_hoc_phan.php">Quay lại</a></p>';
    exit;
}

// Kiểm tra xem sinh viên đã đăng ký học phần này chưa
$check = $conn->query("SELECT * FROM dang_ky WHERE ma_sv = '$ma_sv' AND ma_hoc_phan = '$ma_hoc_phan'");
if ($check->num_rows > 0) {
    echo '<p style="color: red; text-align: center;">Bạn đã đăng ký học phần này rồi! <a href="gio_hang.php">Xem giỏ hàng</a></p>';
    exit;
}

// Bắt đầu giao dịch để đảm bảo tính toàn vẹn dữ liệu
$conn->begin_transaction();

try {
    // Đăng ký học phần
    $sql = "INSERT INTO dang_ky (ma_sv, ma_hoc_phan) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $ma_sv, $ma_hoc_phan);
    $stmt->execute();

    // Giảm số lượng dự kiến
    $sql_update = "UPDATE hoc_phan SET so_luong_du_kien = so_luong_du_kien - 1 WHERE ma_hoc_phan = ?";
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
    echo "Đăng ký thất bại: " . $e->getMessage();
}

$conn->close();
?>