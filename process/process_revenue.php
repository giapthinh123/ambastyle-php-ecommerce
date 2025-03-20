<?php
include '../config/db.php';

// Cập nhật doanh thu hàng ngày
$query = "INSERT INTO revenue (revenue_date, total_orders, total_revenue)
          SELECT CURDATE(), COUNT(id), SUM(total_price) FROM orders WHERE DATE(created_at) = CURDATE()
          ON DUPLICATE KEY UPDATE total_orders = VALUES(total_orders), total_revenue = VALUES(total_revenue)";

mysqli_query($conn, $query);

header("Location: ../admin/admin_revenue.php");
exit();
?>
