<?php
include '../config/db.php';
session_start();

if (isset($_POST['review_id']) && isset($_POST['response']) && isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
    $review_id = intval($_POST['review_id']);
    $response = htmlspecialchars($_POST['response']);
    $admin_id = $_SESSION['user_id'];

    // Kiểm tra xem product_id có được gửi từ form không
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']); // Lấy product_id từ form
    } else {
        // Nếu không có product_id, quay lại trang chính
        header("Location: ../admin/admin_index.php");
        exit();
    }

    // Chèn phản hồi vào CSDL
    $stmt = $conn->prepare("INSERT INTO review_responses (review_id, admin_id, response) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $review_id, $admin_id, $response);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng về trang chi tiết sản phẩm
    header("Location: ../admin/adminProducts.php?id=" . $product_id);
    exit();
} else {
    // Nếu dữ liệu không hợp lệ, quay về trang chính
    header("Location: ../admin/admin_index.php");
    exit();
}
?>
