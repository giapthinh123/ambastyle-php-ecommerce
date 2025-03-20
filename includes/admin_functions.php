<?php
include '../config/db.php';

function getPendingOrders() {
    global $conn;
    $query = "SELECT o.id, u.full_name AS customer_name, o.total_price, o.created_at, o.shipping_address, o.order_status, p.payment_status
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              LEFT JOIN payments p ON o.id = p.order_id
              WHERE o.order_status = 'Chờ xác nhận'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }

    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    return $orders;
}
?>