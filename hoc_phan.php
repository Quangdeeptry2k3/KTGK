<?php
session_start();
require_once 'database.php';
$conn = getConnection();
$result = $conn->query("SELECT * FROM hoc_phan");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Danh Sách Học Phần</title>
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

        <h2>DANH SÁCH HỌC PHẦN</h2>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <p style="color: green; text-align: center;">Bạn đã đăng nhập với tài khoản: <?php echo $_SESSION['username']; ?></p>
        <?php else: ?>
            <p style="color: red; text-align: center;">Vui lòng đăng nhập để đăng ký học phần!</p>
        <?php endif; ?>

        <table>
            <tr>
                <th>Mã Học Phần</th>
                <th>Tên Học Phần</th>
                <th>Số Tín Chỉ</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['ma_hoc_phan']; ?></td>
                <td><?php echo $row['ten_hoc_phan']; ?></td>
                <td><?php echo $row['so_luong_du_kien']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="dang_ky_hoc_phan.php" class="add-btn">Đăng Ký Học Phần</a>
        <?php endif; ?>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>