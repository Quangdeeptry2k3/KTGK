<?php
session_start();
require_once 'database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getConnection();
    $ma_sv = $_POST['ma_sv'];
    $email = $_POST['email']; // Lấy email từ form
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Kiểm tra xem ma_sv đã tồn tại trong bảng users chưa
    $check_ma_sv = $conn->query("SELECT * FROM users WHERE ma_sv = '$ma_sv'");
    if ($check_ma_sv->num_rows > 0) {
        echo "Mã sinh viên đã được đăng ký!";
        exit;
    }

    // Kiểm tra xem email đã tồn tại chưa
    $check_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
        echo "Email đã được đăng ký!";
        exit;
    }

    // Kiểm tra xem ma_sv có tồn tại trong bảng sinh_vien không
    $check_sv = $conn->query("SELECT * FROM sinh_vien WHERE ma_sv = '$ma_sv'");
    if ($check_sv->num_rows == 0) {
        echo "Mã sinh viên không tồn tại trong hệ thống!";
        exit;
    }

    $sql = "INSERT INTO users (ma_sv, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $ma_sv, $email, $password);
    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "Đăng ký thất bại!";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng Ký</title>
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

        <h2>ĐĂNG KÝ</h2>

        <form method="POST">
            <div class="form-group">
                <label for="ma_sv">Mã Sinh Viên:</label>
                <input type="text" name="ma_sv" id="ma_sv" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" id="password" class="input-field" required>
            </div>
            <input type="submit" value="Đăng Ký">
        </form>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>