<?php 
include '../config/db.php';

// Bật báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra kết nối database
if (!$conn) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}

// Thông tin tài khoản admin
$full_name = "Hoàng Văn Tuyên";
$email = "tuyentapta2310@gmail.com";
$password = password_hash("Tuyen2310", PASSWORD_BCRYPT); // Mã hóa mật khẩu
$phone = "0812829498";
$address = "Hà Nội, Việt Nam";
$role = "admin"; // Gán quyền admin

// Kiểm tra xem admin đã tồn tại chưa
$checkQuery = "SELECT id FROM users WHERE email = '$email'";
$checkResult = mysqli_query($conn, $checkQuery);

if (!$checkResult) {
    die("Lỗi truy vấn kiểm tra email: " . mysqli_error($conn));
}

if (mysqli_num_rows($checkResult) > 0) {
    echo "<script>alert('Tài khoản admin đã tồn tại!'); window.location.href='../Pages/login.php';</script>";
    exit();
}

// Thêm admin vào database
$sql = "INSERT INTO users (full_name, email, password, phone, address, role) 
        VALUES ('$full_name', '$email', '$password', '$phone', '$address', '$role')";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Lỗi khi thêm admin: " . mysqli_error($conn));
}

echo "<script>alert('Thêm tài khoản admin thành công!'); window.location.href='../Pages/login.php';</script>";
exit();
?>
