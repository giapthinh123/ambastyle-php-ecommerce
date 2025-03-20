<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['order_id'])) {
        die("<script>alert('Không tìm thấy đơn hàng!'); window.location.href='../Pages/Shop.php';</script>");
    }

    $order_id = intval($_POST['order_id']);
    $user_id = $_SESSION['user_id'];

    // Lấy thông tin đơn hàng
    $query = "SELECT id, total_price FROM orders WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$order) {
        die("<script>alert('Đơn hàng không hợp lệ!'); window.location.href='../Pages/Shop.php';</script>");
    }

    // Xử lý thanh toán (giả định thanh toán thành công)
    $payment_query = "UPDATE orders SET payment_status = 'Paid' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $payment_query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Chuyển hướng về trang thành công
    header("Location: ../Pages/oder_success.php?order_id=$order_id");
    exit();
} else {
    echo "<script>alert('Phương thức không hợp lệ!'); window.location.href='../Pages/Shop.php';</script>";
}
?>
