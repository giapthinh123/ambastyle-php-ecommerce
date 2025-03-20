<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="main-content">
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <form action="../process/login_process.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="login">Đăng Nhập</button>
        </form>
        <p>Chưa có tài khoản? <a href="register.php">Đăng Ký</a></p>
    </div>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
