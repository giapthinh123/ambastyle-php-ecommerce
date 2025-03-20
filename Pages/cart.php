<?php
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Vui lòng đăng nhập để xem giỏ hàng!'); window.location.href='../Pages/login.php';</script>");
}

$user_id = $_SESSION['user_id'];

// Lấy sản phẩm trong giỏ hàng của user
$cart_query = "SELECT cart.id AS cart_id, cart.product_id, cart.quantity, cart.size, 
                      products.name, products.price, products.image_url 
               FROM cart 
               JOIN products ON cart.product_id = products.id 
               WHERE cart.user_id = ?";
$stmt = mysqli_prepare($conn, $cart_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$cart_items = [];
$total_price = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity']; 
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="cart-container">
    <h2>Giỏ Hàng Của Bạn</h2>

    <?php if (empty($cart_items)) { ?>
        <p>Giỏ Hàng Của Bạn Đang Trống!</p>
    <?php } else { ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Size</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                    <th>Thành Tiền</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item) { ?>
                <tr>
                    <td><img src="../<?= htmlspecialchars($item['image_url']) ?>" width="50"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>
                        <form action="../process/update_cart.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <select name="size" onchange="this.form.submit()">
                                <option value="S" <?= $item['size'] == 'S' ? 'selected' : '' ?>>S</option>
                                <option value="M" <?= $item['size'] == 'M' ? 'selected' : '' ?>>M</option>
                                <option value="L" <?= $item['size'] == 'L' ? 'selected' : '' ?>>L</option>
                                <option value="XL" <?= $item['size'] == 'XL' ? 'selected' : '' ?>>XL</option>
                                <option value="XXL" <?= $item['size'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="../process/update_cart.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <button type="submit" name="decrease">-</button>
                            <span><?= $item['quantity'] ?></span>
                            <button type="submit" name="increase">+</button>
                        </form>
                    </td>
                    <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                    <td><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
                    <td>
                        <form action="../process/remove_from_cart.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Tổng tiền: <?= number_format($total_price, 0, ',', '.') ?>đ</h3>

        <a href="checkout.php" class="checkout-button">Mua Hàng</a>
    <?php } ?>
</div>

<script src="../js/script.js"></script>
</body>
</html>

<?php include '../includes/footer.php'; ?>
