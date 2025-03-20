<?php
include '../config/db.php';

// Bật báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['register'])) {
    // Kiểm tra kết nối database
    if (!$conn) {
        die("Lỗi kết nối database: " . mysqli_connect_error());
    }

    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));

    // Kiểm tra email hoặc số điện thoại đã tồn tại chưa
    $checkQuery = "SELECT id FROM users WHERE email = '$email' OR phone = '$phone'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (!$checkResult) {
        die("Lỗi truy vấn kiểm tra email/phone: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Email hoặc số điện thoại đã tồn tại!'); window.location.href='../Pages/register.php';</script>";
        exit();
    }

    // Thêm người dùng vào database (mặc định role = 'customer')
    $sql = "INSERT INTO users (full_name, email, password, phone, address, role) 
            VALUES ('$full_name', '$email', '$password', '$phone', '$address', 'customer')";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Lỗi khi đăng ký: " . mysqli_error($conn));
    }
    header("Location: ../Pages/login.php?message=success");
    exit();
}
?>