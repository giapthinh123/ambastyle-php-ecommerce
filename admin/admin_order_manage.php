<?php
include '../config/db.php';
include '../includes/admin_functions.php'; // Gọi file chứa hàm xử lý
include '../includes/admin_header.php'; 
// Lấy danh sách đơn hàng "Chờ xác nhận"
$pending_orders = getPendingOrders();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản Lý Đơn Hàng</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="main-content">
    <div class="table-container">
    <h2>Quản Lý Đơn Hàng</h2>
    <?php if (!empty($pending_orders)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách Hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Ngày Đặt</th>
                        <th>Địa Chỉ</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                        <th>Thanh Toán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_orders as $order) { ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                            <td><?= $order['created_at'] ?></td>
                            <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                            <td><?= $order['order_status'] ?></td>
                            <td>
                                <form action="../process/process_order.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button type="submit" name="approve">Xác Nhận</button>
                                    <button type="submit" name="cancel" onclick="return confirm('Bạn có chắc muốn hủy đơn này?');">Hủy</button>
                                </form>
                            </td>
                            <td>
    <form class="payment-form">
        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
        <?php if ($order['payment_status'] == 'Chưa thanh toán') { ?>
            <input type="text" name="transaction_id" placeholder="Mã giao dịch">
            <button type="button" class="confirm-payment-btn">Xác Nhận Thanh Toán</button>
        <?php } else { ?>
            <span>Đã Thanh Toán</span>
        <?php } ?>
    </form>
</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Không có đơn hàng nào.</p>
        <?php } ?>
        <!-- Form xử lý Xác nhận thanh toán -->
  <!-- Form xử lý Xác nhận thanh toán (Truy vấn trực tiếp từ MySQL) -->
  <h3>Xác Nhận Thanh Toán</h3>
    <form action="../process/process_tt.php" method="POST">
        <label for="order_id">Chọn đơn hàng:</label>
        <select name="order_id" required>
            <option value="">-- Chọn đơn --</option>
            <?php 
            $paymentQuery = "SELECT id FROM Orders WHERE id NOT IN (SELECT order_id FROM Payments WHERE payment_status = 'Đã thanh toán')";
            $paymentResult = mysqli_query($conn, $paymentQuery);
            while ($order = mysqli_fetch_assoc($paymentResult)) { ?>
                <option value="<?= $order['id'] ?>">#<?= $order['id'] ?></option>
            <?php } ?>
        </select>
        <input type="text" name="transaction_id" placeholder="Mã giao dịch" required>
        <button type="submit" name="confirm_payment">Xác nhận thanh toán</button>
    </form>
    </div>
</div>
<script>
$(document).ready(function() {
    $(".confirm-payment-btn").click(function() {
        let form = $(this).closest(".payment-form");
        let order_id = form.find("input[name='order_id']").val();
        let transaction_id = form.find("input[name='transaction_id']").val();

        if (transaction_id.trim() === "") {
            alert("Vui lòng nhập mã giao dịch!");
            return;
        }

        $.ajax({
            url: "../process/process_tt.php",
            type: "POST",
            data: {
                confirm_payment: true,
                order_id: order_id,
                transaction_id: transaction_id
            },
            success: function(response) {
                alert(response);
                location.reload(); // Cập nhật lại trang sau khi thanh toán
            },
            error: function() {
                alert("Có lỗi xảy ra khi xác nhận thanh toán!");
            }
        });
    });
});
</script>
</body>
</html>
<?php include '../includes/footer.php'; ?>