<?php
session_start();
require_once 'database.php';
$conn = getConnection();
$ma_sv = $_GET['ma_sv'];
$result = $conn->query("SELECT * FROM sinh_vien WHERE ma_sv = '$ma_sv'");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thông Tin Chi Tiết</title>
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

        <h2>THÔNG TIN CHI TIẾT</h2>
        <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); max-width: 500px; margin: 0 auto;">
            <p><strong>Mã SV:</strong> <?php echo $row['ma_sv']; ?></p>
            <p><strong>Họ Tên:</strong> <?php echo $row['ho_ten']; ?></p>
            <p><strong>Giới Tính:</strong> <?php echo $row['gioi_tinh']; ?></p>
            <p><strong>Ngày Sinh:</strong> <?php echo $row['ngay_sinh']; ?></p>
            <p><strong>Hình:</strong> 
                <?php if (!empty($row['hinh']) && file_exists($row['hinh'])): ?>
                    <img src="<?php echo $row['hinh']; ?>" alt="Hình sinh viên" width="100">
                <?php else: ?>
                    <span>Không có hình</span>
                <?php endif; ?>
            </p>
            <p><strong>Mã Ngành:</strong> <?php echo $row['ma_nganh']; ?></p>
        </div>
        <a href="index.php">Quay Lại</a>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>