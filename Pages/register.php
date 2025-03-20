<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="main-content">
    <div class="register-container">
        <h2>Đăng Ký</h2>
        <form action="../process/register_process.php" method="POST">
            <input type="text" name="full_name" placeholder="Họ và tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="text" name="phone" placeholder="Số điện thoại" required>
            <textarea name="address" placeholder="Địa chỉ"></textarea>
            <button type="submit" name="register">Đăng Ký</button>
        </form>
        <p>Đã có tài khoản? <a href="login.php">Đăng Nhập</a></p>
    </div>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>