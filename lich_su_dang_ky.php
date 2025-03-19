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

// Lấy danh sách học phần đã lưu
$saved_courses = $conn->query("SELECT ls.id, ls.ma_hoc_phan, hp.ten_hoc_phan, ls.ngay_luu 
                               FROM lich_su_dang_ky ls 
                               JOIN hoc_phan hp ON ls.ma_hoc_phan = hp.ma_hoc_phan 
                               WHERE ls.ma_sv = '$ma_sv' 
                               ORDER BY ls.ngay_luu DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lịch Sử Đăng Ký</title>
    <link rel="stylesheet" href="asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="menu">
            <a href="#">Test1</a>
            <a href="index.php">Sinh Viên</a>
            <a href="hoc_phan.php">Học Phần</a>
            <a href="gio_hang.php">Giỏ Hàng</a>
            <a href="lich_su_dang_ky.php">Lịch Sử Đăng Ký</a>
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
                <a href="register.php">Đăng Ký</a>
                <a href="login.php">Đăng Nhập</a>
            <?php else: ?>
                <span style="color: white; padding: 15px;">Xin chào, <?php echo $_SESSION['ma_sv']; ?></span>
                <a href="logout.php">Đăng Xuất</a>
            <?php endif; ?>
        </div>

        <h2>LỊCH SỬ ĐĂNG KÝ</h2>

        <div class="saved-items">
            <h3>Danh Sách Học Phần Đã Lưu</h3>
            <?php if ($saved_courses->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Mã Học Phần</th>
                        <th>Tên Học Phần</th>
                        <th>Ngày Lưu</th>
                    </tr>
                    <?php while ($course = $saved_courses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $course['ma_hoc_phan']; ?></td>
                        <td><?php echo $course['ten_hoc_phan']; ?></td>
                        <td><?php echo $course['ngay_luu']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p style="color: red; text-align: center;">Bạn chưa lưu học phần nào!</p>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="gio_hang.php" class="add-btn">Quay Lại Giỏ Hàng</a>
        </div>
    </div>
    <table>
    <tr>
        <th>Mã Học Phần</th>
        <th>Tên Học Phần</th>
        <th>Ngày Lưu</th>
        <th>Hành Động</th>
    </tr>
    <?php while ($course = $saved_courses->fetch_assoc()): ?>
    <tr>
        <td><?php echo $course['ma_hoc_phan']; ?></td>
        <td><?php echo $course['ten_hoc_phan']; ?></td>
        <td><?php echo $course['ngay_luu']; ?></td>
        <td>
            <a href="xoa_lich_su_dang_ky.php?id=<?php echo $course['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa học phần này khỏi lịch sử?')">Xóa</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>