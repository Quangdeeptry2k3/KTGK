<?php
session_start();
require_once 'database.php';

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = getConnection();
$ma_sv = $_GET['ma_sv'];
$result = $conn->query("SELECT * FROM sinh_vien WHERE ma_sv = '$ma_sv'");
$row = $result->fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ho_ten = $_POST['ho_ten'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $hinh = $row['hinh'];
    if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] == 0) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $hinh = 'uploads/' . basename($_FILES['hinh']['name']);
        move_uploaded_file($_FILES['hinh']['tmp_name'], $hinh);
    }
    $ma_nganh = $_POST['ma_nganh'];
    if ($hinh != $row['hinh']) {
        $sql = "UPDATE sinh_vien SET ho_ten=?, gioi_tinh=?, ngay_sinh=?, hinh=?, ma_nganh=? WHERE ma_sv=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $ho_ten, $gioi_tinh, $ngay_sinh, $hinh, $ma_nganh, $ma_sv);
    } else {
        $sql = "UPDATE sinh_vien SET ho_ten=?, gioi_tinh=?, ngay_sinh=?, ma_nganh=? WHERE ma_sv=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $ho_ten, $gioi_tinh, $ngay_sinh, $ma_nganh, $ma_sv);
    }
    $stmt->execute();
    $conn->close();
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sửa Sinh Viên</title>
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

        <h2>SỬA SINH VIÊN</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ho_ten">Họ Tên:</label>
                <input type="text" name="ho_ten" id="ho_ten" value="<?php echo $row['ho_ten']; ?>" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="gioi_tinh">Giới Tính:</label>
                <select name="gioi_tinh" id="gioi_tinh" class="input-field">
                    <option <?php if($row['gioi_tinh']=="Nam") echo "selected"; ?> value="Nam">Nam</option>
                    <option <?php if($row['gioi_tinh']=="Nữ") echo "selected"; ?> value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="ngay_sinh">Ngày Sinh:</label>
                <input type="date" name="ngay_sinh" id="ngay_sinh" value="<?php echo $row['ngay_sinh']; ?>" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="hinh">Hình:</label>
                <input type="file" name="hinh" id="hinh" class="input-field">
            </div>
            <div class="form-group">
                <label for="ma_nganh">Mã Ngành:</label>
                <input type="text" name="ma_nganh" id="ma_nganh" value="<?php echo $row['ma_nganh']; ?>" class="input-field" required>
            </div>
            <input type="submit" value="Lưu">
        </form>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>