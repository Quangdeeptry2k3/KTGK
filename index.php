<?php
session_start();
require_once 'database.php';
$conn = getConnection();
$result = $conn->query("SELECT * FROM sinh_vien");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Trang Sinh Viên</title>
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

        <h2>TRANG SINH VIÊN</h2>

        <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
            <p style="color: red; text-align: center;">Vui lòng đăng nhập để thêm, sửa hoặc xóa sinh viên!</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="create.php" class="add-btn">Add Student</a>
        <?php endif; ?>

        <table>
            <tr>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Giới Tính</th>
                <th>Ngày Sinh</th>
                <th>Hình</th>
                <th>Mã Ngành</th>
                <th>Hành Động</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['ma_sv']; ?></td>
                <td><?php echo $row['ho_ten']; ?></td>
                <td><?php echo $row['gioi_tinh']; ?></td>
                <td><?php echo $row['ngay_sinh']; ?></td>
                <td>
                    <?php if (!empty($row['hinh']) && file_exists($row['hinh'])): ?>
                        <img src="<?php echo $row['hinh']; ?>" alt="Hình sinh viên" width="100">
                    <?php else: ?>
                        <span>Không có hình</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['ma_nganh']; ?></td>
                <td>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <a href="edit.php?ma_sv=<?php echo $row['ma_sv']; ?>">Edit</a>
                        <a href="detail.php?ma_sv=<?php echo $row['ma_sv']; ?>">Detail</a>
                        <a href="delete.php?ma_sv=<?php echo $row['ma_sv']; ?>" onclick="return confirm('Bạn có chắc?')">Delete</a>
                    <?php else: ?>
                        <a href="detail.php?ma_sv=<?php echo $row['ma_sv']; ?>">Detail</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>