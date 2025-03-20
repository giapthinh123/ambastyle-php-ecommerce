<?php
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem thông tin đơn hàng!'); window.location.href='../Pages/login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của người dùng
$query = "SELECT o.id, o.total_price, o.created_at, o.order_status, s.status AS shipping_status
          FROM orders o
          LEFT JOIN shipping_status s ON o.id = s.order_id
          WHERE o.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Danh Sách Đơn Hàng</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="main-content">
    <div class="order-list-container">
        <h2>Danh sách đơn hàng</h2>
        <?php if (!empty($orders)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Tổng Tiền</th>
                        <th>Ngày Đặt</th>
                        <th>Trạng Thái Đơn Hàng</th>
                        <th>Trạng Thái Vận Chuyển</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                            <td><?= $order['created_at'] ?></td>
                            <td><?= $order['order_status'] ?></td>
                            <td><?= $order['shipping_status'] ?? 'Chưa có thông tin' ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Không có đơn hàng nào.</p>
        <?php } ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>