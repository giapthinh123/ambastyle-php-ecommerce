<?php
include '../config/db.php';
include '../includes/adminShipping.php'; // Gọi file chứa hàm xử lý
include '../includes/admin_header.php'; 

// Lấy danh sách tất cả các đơn hàng
$all_orders = getAllOrders();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản Lý Đơn Hàng</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<div class="main-content">
    <div class="table-container">
    <h2>Đơn Hàng</h2>
    <?php if (!empty($all_orders)) { ?>
        <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách Hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Ngày Đặt</th>
                        <th>Địa Chỉ</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_orders as $order) { ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                            <td><?= $order['created_at'] ?></td>
                            <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                            <td><?= $order['order_status'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Không có đơn hàng nào.</p>
        <?php } ?>
    </div>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>