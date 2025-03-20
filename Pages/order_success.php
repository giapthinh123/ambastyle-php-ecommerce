<?php 
include '../includes/header.php';
include '../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Vui lòng đăng nhập để xem đơn hàng!'); window.location.href='../Pages/login.php';</script>");
}

$user_id = $_SESSION['user_id'];

// Kiểm tra xem có ID đơn hàng không
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("<script>alert('Không tìm thấy đơn hàng!'); window.location.href='../Pages/Shop.php';</script>");
}

$order_id = intval($_GET['order_id']);

// Lấy thông tin đơn hàng
$query = "SELECT o.id, o.total_price, o.created_at AS order_date, p.payment_status 
          FROM orders o 
          LEFT JOIN payments p ON o.id = p.order_id
          WHERE o.id = ? AND o.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$order) {
    die("<script>alert('Đơn hàng không tồn tại!'); window.location.href='../Pages/Shop.php';</script>");
}

// Lấy thông tin trạng thái vận chuyển
$shipping_query = "SELECT status, updated_at FROM shipping_status WHERE order_id = ?";
$stmt = mysqli_prepare($conn, $shipping_query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$shipping_result = mysqli_stmt_get_result($stmt);
$shipping = mysqli_fetch_assoc($shipping_result);
mysqli_stmt_close($stmt);

$shipping_status = $shipping ? $shipping['status'] : "Chưa có thông tin";
$updated_at = $shipping ? $shipping['updated_at'] : "-";

// Lấy danh sách sản phẩm trong đơn hàng
$items_query = "SELECT p.name, p.image_url, od.quantity, od.price 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
$stmt = mysqli_prepare($conn, $items_query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$items_result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="order-container">
        <h2>Thông tin đơn hàng #<?= $order_id ?></h2>
        <div class="order-info">
            <p><strong>Ngày đặt:</strong> <?= $order['order_date'] ?></p>
            <p><strong>Trạng thái thanh toán:</strong> <?= $order['payment_status'] ?></p>
            <p><strong>Trạng thái vận chuyển:</strong> <?= $shipping_status ?> (Cập nhật: <?= $updated_at ?>)</p>
            <p><strong>Tổng tiền:</strong> <?= number_format($order['total_price'], 0, ',', '.') ?>đ</p>
        </div>

        <h3>Chi tiết đơn hàng</h3>
        <div class="order-items">
            <?php while ($item = mysqli_fetch_assoc($items_result)) { ?>
                <div class="order-item">
                    <img src="../<?= htmlspecialchars($item['image_url']) ?>" width="50">
                    <p><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)</p>
                    <p><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</p>
                </div>
            <?php } ?>
        </div>

        <a href="../Pages/Shop.php" class="back-btn">Quay lại danh sách đơn hàng</a>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>

<?php include '../includes/footer.php'; ?>