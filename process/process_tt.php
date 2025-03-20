<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_payment'])) {
    $order_id = $_POST['order_id'];
    $transaction_id = $_POST['transaction_id'];

    if (empty($order_id) || empty($transaction_id)) {
        echo "Thiếu dữ liệu!";
        exit;
    }

    // Cập nhật trạng thái thanh toán
    $updatePayment = "UPDATE Payments SET payment_status = 'Đã thanh toán', transaction_id = ?, paid_at = NOW() WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $updatePayment);
    mysqli_stmt_bind_param($stmt, "si", $transaction_id, $order_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Lấy thông tin đơn hàng
        $query = "SELECT total_price FROM orders WHERE id = $order_id";
        $result = mysqli_query($conn, $query);
        $order = mysqli_fetch_assoc($result);

        if ($order) {
            $total_price = $order['total_price'];

            // Cập nhật doanh thu hàng ngày
            $query = "INSERT INTO revenue (revenue_date, total_orders, total_revenue)
                      VALUES (CURDATE(), 1, $total_price)
                      ON DUPLICATE KEY UPDATE total_orders = total_orders + 1, total_revenue = total_revenue + VALUES(total_revenue)";
            mysqli_query($conn, $query);
        }

        echo "Thanh toán thành công!";
    } else {
        echo "Lỗi khi xác nhận thanh toán!";
    }
}
?>
