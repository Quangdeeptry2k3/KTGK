<?php
require_once 'database.php';
session_start();
$conn = getConnection();
$ma_sv = "1234567890"; // Giả sử mã sinh viên đã đăng nhập (thay bằng session thực tế)
$result = $conn->query("SELECT dh.*, hp.ten_hoc_phan, hp.so_luong_du_kien 
                        FROM dang_ky_hoc_phan dh 
                        JOIN hoc_phan hp ON dh.ma_hoc_phan = hp.ma_hoc_phan 
                        WHERE dh.ma_sv = '$ma_sv'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng Ký Học Phần</title>
    <link rel="stylesheet" href="asset/style.css">
</head>
<body>
    <!-- Menu -->
    <div class="menu">
        <a href="#">Test1</a>
        <a href="index.php">Sinh Viên</a>
        <a href="hoc_phan.php">Học Phần</a>
        <a href="dang_ky_form.php">Đăng Ký</a>
        <a href="login.php">Đăng Nhập</a>
    </div>

    <h2>ĐĂNG KÝ HỌC PHẦN</h2>

    <!-- Form hiển thị học phần đã đăng ký -->
    <h3>Danh sách học phần đã đăng ký:</h3>
    <table>
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Hành Động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['ma_hoc_phan']; ?></td>
            <td><?php echo $row['ten_hoc_phan']; ?></td>
            <td><?php echo $row['so_luong_du_kien']; ?></td> <!-- Sử dụng tạm thời -->
            <td>
                <a href="xoa_dang_ky.php?id=<?php echo $row['id']; ?>" class="add-btn" style="background-color: #f44336; padding: 5px 10px;">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Nút xóa tất cả -->
    <a href="xoa_het.php" class="add-btn" style="background-color: #f44336; padding: 5px 10px; margin-top: 10px;">Xóa Tất Cả</a>
</body>
</html>
<?php $conn->close(); ?>