<?php
session_start();
require_once 'database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getConnection();
    $ma_sv = $_POST['ma_sv'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE ma_sv = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_sv);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['ma_sv'] = $ma_sv; // Lưu ma_sv vào session
            header("Location: index.php");
            exit;
        } else {
            echo "Mật khẩu không đúng!";
        }
    } else {
        echo "Mã sinh viên không tồn tại!";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng Nhập</title>
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

        <h2>ĐĂNG NHẬP</h2>

        <form method="POST">
            <div class="form-group">
                <label for="ma_sv">Mã Sinh Viên:</label>
                <input type="text" name="ma_sv" id="ma_sv" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" id="password" class="input-field" required>
            </div>
            <input type="submit" value="Đăng Nhập">
        </form>
    </div>

    <footer>
        <p>© 2025 Quản Lý Sinh Viên. All rights reserved.</p>
    </footer>
</body>
</html>