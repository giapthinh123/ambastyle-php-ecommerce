<?php
include '../config/db.php';

// Bật báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {
    // Kiểm tra kết nối database
    if (!$conn) {
        die("Lỗi kết nối database: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    // Kiểm tra email có tồn tại không
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (!$checkResult) {
        die("Lỗi truy vấn kiểm tra email: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($checkResult) == 0) {
        echo "<script>alert('Email không tồn tại!'); window.location.href='../Pages/login.php';</script>";
        exit();
    }

    $user = mysqli_fetch_assoc($checkResult);

    // Kiểm tra mật khẩu
    if (!password_verify($password, $user['password'])) {
        echo "<script>alert('Mật khẩu không đúng!'); window.location.href='../Pages/login.php';</script>";
        exit();
    }

    // Đăng nhập thành công, lưu thông tin người dùng vào session
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    header("Location: ../Pages/index.php?message=success");
    exit();
}
?>