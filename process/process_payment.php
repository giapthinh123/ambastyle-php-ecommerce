<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $city = $_POST['city'] ?? '';
    $district = $_POST['district'] ?? '';
    $ward = $_POST['ward'] ?? '';
    $city_name = $_POST['city_name'] ?? '';
    $district_name = $_POST['district_name'] ?? '';
    $ward_name = $_POST['ward_name'] ?? '';
    $shipping_address = $_POST['shipping_address'] ?? '';
    $promo_code = $_POST['promo_code'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $cart_items = json_decode($_POST['cart_items'], true) ?? [];
    $total_price = $_POST['total_price'] ?? 0.0;

    // Xử lý mã giảm giá (nếu có)
    if (!empty($promo_code)) {
        $promo_query = "SELECT * FROM promotions WHERE code = ? AND start_date <= CURDATE() AND end_date >= CURDATE()";
        $stmt = mysqli_prepare($conn, $promo_query);
        mysqli_stmt_bind_param($stmt, "s", $promo_code);
        mysqli_stmt_execute($stmt);
        $promo_result = mysqli_stmt_get_result($stmt);
        $promotion = mysqli_fetch_assoc($promo_result);
        mysqli_stmt_close($stmt);

        if ($promotion) {
            $discount = ($promotion['discount_percentage'] / 100) * $total_price;
            $total_price -= $discount;
        }
    }

    $full_address = "$shipping_address, $ward_name, $district_name, $city_name";
    // Lưu thông tin đơn hàng vào cơ sở dữ liệu
    $order_query = "INSERT INTO orders (user_id, total_price, shipping_address) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $order_query);
    mysqli_stmt_bind_param($stmt, "ids", $user_id, $total_price, $full_address);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Lưu thông tin sản phẩm trong đơn hàng và trừ số lượng sản phẩm trong kho
    foreach ($cart_items as $item) {
        $order_item_query = "INSERT INTO order_details (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $order_item_query);
        mysqli_stmt_bind_param($stmt, "iiids", $order_id, $item['product_id'], $item['quantity'], $item['price'], $item['size']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Trừ số lượng sản phẩm trong kho
        $update_stock_query = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_stock_query);
        mysqli_stmt_bind_param($stmt, "ii", $item['quantity'], $item['product_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Lưu thông tin thanh toán
    $payment_query = "INSERT INTO payments (order_id, user_id, amount, payment_method) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $payment_query);
    mysqli_stmt_bind_param($stmt, "iids", $order_id, $user_id, $total_price, $payment_method);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Xóa giỏ hàng của người dùng
    $delete_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $delete_cart_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Chuyển hướng đến trang order_success.php
    header("Location: ../Pages/order_success.php?order_id=$order_id");
    exit();
} else {
    echo "<script>alert('Phương thức không hợp lệ!'); window.location.href='../Pages/Shop.php';</script>";
}
?>