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

// Lấy thông tin sinh viên
$sinh_vien = $conn->query("SELECT * FROM sinh_vien WHERE ma_sv = '$ma_sv'");
$sinh_vien_row = $sinh_vien->fetch_assoc();

// Lấy danh sách học phần đã đăng ký
$registered_courses = $conn->query("SELECT dk.ma_hoc_phan, hp.ten_hoc_phan, hp.so_luong_du_kien 
                                    FROM dang_ky dk 
                                    JOIN hoc_phan hp ON dk.ma_hoc_phan = hp.ma_hoc_phan 
                                    WHERE dk.ma_sv = '$ma_sv'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="menu">
            <a href="#">Test1</a>
            <a href="index.php">Sinh Viên</a>
            <a href="hoc_phan.php">Học Phần</a>
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
                <a href="register.php">Đăng Ký</a>
                <a href="login.php">Đăng Nhập</a>
            <?php else: ?>
                <a href="logout.php">Đăng Xuất</a>
            <?php endif; ?>
        </div>

        <h2>GIỎ HÀNG</h2>

        <!-- Thông tin sinh viên -->
        <div class="student-info">
            <h3>Thông Tin Sinh Viên</h3>
            <?php if ($sinh_vien_row): ?>
                <p><strong>Mã SV:</strong> <?php echo $sinh_vien_row['ma_sv']; ?></p>
                <p><strong>Họ Tên:</strong> <?php echo $sinh_vien_row['ho_ten']; ?></p>
                <p><strong>Giới Tính:</strong> <?php echo $sinh_vien_row['gioi_tinh']; ?></p>
                <p><strong>Ngày Sinh:</strong> <?php echo $sinh_vien_row['ngay_sinh']; ?></p>
                <p><strong>Hình:</strong> 
                    <?php if (!empty($sinh_vien_row['hinh']) && file_exists($sinh_vien_row['hinh'])): ?>
                        <img src="<?php echo $sinh_vien_row['hinh']; ?>" alt="Hình sinh viên" width="100">
                    <?php else: ?>
                        <span>Không có hình</span>
                    <?php endif; ?>
                </p>
                <p><strong>Mã Ngành:</strong> <?php echo $sinh_vien_row['ma_nganh']; ?></p>
            <?php else: ?>
                <p style="color: red; text-align: center;">Không tìm thấy thông tin sinh viên!</p>
            <?php endif; ?>
        </div>

        <!-- Danh sách học phần đã đăng ký -->
        <div class="cart-items">
            <h3>Danh Sách Học Phần Đã Đăng Ký</h3>
            <?php if ($registered_courses->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Mã Học Phần</th>
                        <th>Tên Học Phần</th>
                        <th>Số Tín Chỉ</th>
                        <th>Hành Động</th>
                    </tr>
                    <?php while ($course = $registered_courses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $course['ma_hoc_phan']; ?></td>
                        <td><?php echo $course['ten_hoc_phan']; ?></td>
                        <td><?php echo $course['so_luong_du_kien']; ?></td>
                        <td>
                            <a href="xoa_hoc_phan.php?ma_hoc_phan=<?php echo $course['ma_hoc_phan']; ?>" onclick="return confirm('Bạn có chắc muốn xóa học phần này khỏi giỏ hàng?')">Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p style="color: red; text-align: center;">Bạn chưa đăng ký học phần nào!</p>
            <?php endif; ?>
        </div>

        <a href="dang_ky_hoc_phan.php" class="add-btn">Tiếp Tục Đăng Ký</a>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>