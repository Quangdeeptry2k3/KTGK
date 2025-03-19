<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
$conn = getConnection();
$result = $conn->query("SELECT * FROM hoc_phan");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng Ký Học Phần</title>
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
                <span style="color: white; padding: 15px;">Xin chào, <?php echo $_SESSION['ma_sv']; ?></span>
                <a href="logout.php">Đăng Xuất</a>
            <?php endif; ?>
        </div>

        <h2>ĐĂNG KÝ HỌC PHẦN</h2>

        <form method="GET" action="dang_ky.php">
            <div class="form-group">
                <label for="ma_hoc_phan">Chọn Học Phần:</label>
                <select name="ma_hoc_phan" id="ma_hoc_phan" class="input-field" required>
                    <option value="">-- Chọn học phần --</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['ma_hoc_phan']; ?>" <?php echo $row['so_luong_du_kien'] <= 0 ? 'disabled' : ''; ?>>
                            <?php echo $row['ten_hoc_phan']; ?> (Mã: <?php echo $row['ma_hoc_phan']; ?>, Còn: <?php echo $row['so_luong_du_kien']; ?> chỗ)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <input type="submit" value="Đăng Ký">
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="gio_hang.php" class="add-btn">Xem Giỏ Hàng</a>
        </div>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>