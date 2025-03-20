<?php
include '../config/db.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $ward = $_POST['ward'];
    $city_name = $_POST['city_name'];
    $district_name = $_POST['district_name'];
    $ward_name = $_POST['ward_name'];
    $shipping_address = $_POST['shipping_address'];
    $promo_code = $_POST['promo_code'];
    $cart_items = json_decode($_POST['cart_items'], true);
    $total_price = $_POST['total_price'];
} else {
    die("<script>alert('Phương thức không hợp lệ!'); window.location.href='../Pages/Shop.php';</script>");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Xác Nhận Thanh Toán</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="main-content">
    <div class="confirm-payment-container">
        <h2>Xác Nhận Thanh Toán</h2>
        <form action="../process/process_payment.php" method="POST">
            <input type="hidden" name="fullname" value="<?= htmlspecialchars($fullname) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>">
            <input type="hidden" name="city" value="<?= htmlspecialchars($city) ?>">
            <input type="hidden" name="district" value="<?= htmlspecialchars($district) ?>">
            <input type="hidden" name="ward" value="<?= htmlspecialchars($ward) ?>">
            <input type="hidden" name="city_name" value="<?= htmlspecialchars($city_name) ?>">
            <input type="hidden" name="district_name" value="<?= htmlspecialchars($district_name) ?>">
            <input type="hidden" name="ward_name" value="<?= htmlspecialchars($ward_name) ?>">
            <input type="hidden" name="shipping_address" value="<?= htmlspecialchars($shipping_address) ?>">
            <input type="hidden" name="promo_code" value="<?= htmlspecialchars($promo_code) ?>">
            <input type="hidden" name="cart_items" value='<?= json_encode($cart_items) ?>'>
            <input type="hidden" name="total_price" value="<?= $total_price ?>">

            <label>Chọn phương thức thanh toán:</label>
            <select name="payment_method" required>
                <option value="">Chọn phương thức thanh toán</option>
                <option value="Credit Card">Thẻ tín dụng</option>
                <option value="Bank Transfer">Chuyển khoản ngân hàng</option>
                <option value="COD">Thanh toán khi nhận hàng</option>
            </select>
            <button type="submit" name="confirm_payment">Xác Nhận Thanh Toán</button>
        </form>
    </div>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>