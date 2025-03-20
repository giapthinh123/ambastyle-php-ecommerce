<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webquanaonam";

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đặt bộ mã ký tự UTF-8 để hiển thị đúng tiếng Việt
$conn->set_charset("utf8mb4");

?>
