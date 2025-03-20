<?php
include '../config/db.php';
session_start();

if (isset($_POST['product_id']) && isset($_POST['size']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    // Kiểm tra sản phẩm có tồn tại và còn hàng không
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    if ($product && $product['stock'] >= $quantity) {
        // Thêm sản phẩm vào giỏ hàng
        $sql = "INSERT INTO cart (user_id, product_id, size, quantity) VALUES ($user_id, $product_id, '$size', $quantity)
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
        if (mysqli_query($conn, $sql)) {
            // Truy vấn lại tổng số lượng sản phẩm trong giỏ hàng
            $sql = "SELECT SUM(quantity) as total_items FROM cart WHERE user_id = $user_id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $cart_count = $row['total_items'] ?? 0;

            // Trả về phản hồi JSON thành công
            echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng!', 'cart_count' => $cart_count]);
        } else {
            // Trả về phản hồi JSON lỗi
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm sản phẩm vào giỏ hàng.']);
        }
    } else {
        // Trả về phản hồi JSON lỗi
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc không đủ hàng.']);
    }
} else {
    // Trả về phản hồi JSON lỗi
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
}
?>