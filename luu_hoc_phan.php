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

// Lấy danh sách học phần đã đăng ký
$registered_courses = $conn->query("SELECT ma_hoc_phan FROM dang_ky WHERE ma_sv = '$ma_sv'");

if ($registered_courses->num_rows == 0) {
    echo '<p style="color: red; text-align: center;">Bạn chưa đăng ký học phần nào để lưu! <a href="gio_hang.php">Quay lại</a></p>';
    exit;
}

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Lưu từng học phần vào bảng lich_su_dang_ky
    while ($course = $registered_courses->fetch_assoc()) {
        $ma_hoc_phan = $course['ma_hoc_phan'];
        $ngay_luu = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại

        $sql = "INSERT INTO lich_su_dang_ky (ma_sv, ma_hoc_phan, ngay_luu) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $ma_sv, $ma_hoc_phan, $ngay_luu);
        $stmt->execute();
    }

    // Xác nhận giao dịch
    $conn->commit();

    // Thêm thông báo thành công
    $_SESSION['message'] = '<p style="color: green; text-align: center;">Lưu học phần thành công! <a href="lich_su_dang_ky.php">Xem lịch sử đăng ký</a></p>';
    header("Location: gio_hang.php");
    exit;
} catch (Exception $e) {
    // Nếu có lỗi, hủy giao dịch
    $conn->rollback();
    echo "Lưu học phần thất bại: " . $e->getMessage();
}

$conn->close();
?>