<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $review = trim($_POST['review']);

    // Kiểm tra khách hàng đã mua sản phẩm chưa
    $sql = "SELECT COUNT(*) 
    FROM order_details od
    JOIN orders o ON od.order_id = o.id
    WHERE o.user_id = ? AND od.product_id = ?";

$check_purchase = $conn->prepare($sql);
$check_purchase->bind_param("ii", $user_id, $product_id);
$check_purchase->execute();
$check_purchase->bind_result($has_purchased);
$check_purchase->fetch();
$check_purchase->close();


    if ($has_purchased > 0) {
        // Thêm đánh giá vào bảng product_reviews
        $stmt = $conn->prepare("INSERT INTO product_reviews (user_id, product_id, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $product_id, $rating, $review);
        if ($stmt->execute()) {
            echo "<script>alert('Cảm ơn bạn đã đánh giá!'); window.location.href='../Pages/productDetail.php?id=$product_id';</script>";
        } else {
            echo "<script>alert('Lỗi khi gửi đánh giá!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Bạn chỉ có thể đánh giá sản phẩm đã mua!');</script>";
    }
} else {
    echo "<script>alert('Vui lòng đăng nhập để gửi đánh giá!'); window.location.href='../Pages/Login.php';</script>";
}
?>
