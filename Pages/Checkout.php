<?php 
include '../config/db.php';  
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Vui lòng đăng nhập để thanh toán!'); window.location.href='../Pages/login.php';</script>");
}

$user_id = $_SESSION['user_id'];
$cart_items = [];
$total_price = 0;

// Kiểm tra nếu "Mua ngay" từ ProductDetail.php
if (isset($_POST['direct_buy']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $size = htmlspecialchars($_POST['size']);
    $quantity = intval($_POST['quantity']) > 0 ? intval($_POST['quantity']) : 1;

    // Lấy thông tin sản phẩm
    $sql = "SELECT id, name, price, image_url FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($product) {
        $cart_items[] = [
            'product_id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image_url' => $product['image_url'],
            'quantity' => $quantity,
            'size' => $size
        ];
        $total_price = $product['price'] * $quantity;
    }
} else {
    // Lấy sản phẩm từ giỏ hàng
    $cart_query = "SELECT cart.*, products.name, products.price, products.image_url
                   FROM cart 
                   JOIN products ON cart.product_id = products.id 
                   WHERE cart.user_id = ?";
    $stmt = mysqli_prepare($conn, $cart_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity']; 
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-form">
            <h2>Thông Tin Thanh Toán</h2>
            <form action="../Pages/confirm_payment.php" method="POST">
                <label>Họ Và Tên:</label>
                <input type="text" name="fullname" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Số Điện Thoại:</label>
                <input type="text" name="phone" required>

                <label>Địa Chỉ Giao Hàng:</label>
                <select name="city" id="city" required>
                    <option value="">Chọn tỉnh/thành phố</option>
                </select>

                <select name="district" id="district" required>
                    <option value="">Chọn quận/huyện</option>
                </select>

                <select name="ward" id="ward" required>
                    <option value="">Chọn phường/xã</option>
                </select>

                <input type="hidden" name="city_name" id="city_name">
                <input type="hidden" name="district_name" id="district_name">
                <input type="hidden" name="ward_name" id="ward_name">

                <label>Địa Chỉ Cụ Thể (Số nhà, đường, ngõ...):</label>
                <input type="text" name="shipping_address" required>
                <label>Mã Giảm Giá (nếu có):</label>
                <input type="text" name="promo_code">
                
                <input type="hidden" name="cart_items" value='<?= json_encode($cart_items) ?>'>
                <input type="hidden" name="total_price" value="<?= $total_price ?>">
                <button type="submit" name="place_order">Thanh toán</button>
            </form>
        </div>
        <div class="cart-summary">
            <h3>Đơn Hàng Của Bạn</h3>
            <?php foreach ($cart_items as $item) { ?>
                <div class="cart-item">
                    <img src="../<?= htmlspecialchars($item['image_url']) ?>" width="50">
                    <p><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>, Size: <?= htmlspecialchars($item['size'] ?? 'Không có') ?>)</p>
                    <p><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</p>
                </div>
            <?php } ?>
            <h3>Tổng Tiền: <?= number_format($total_price, 0, ',', '.') ?>đ</h3>
        </div>
    </div>
    <script src="..js/script.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>