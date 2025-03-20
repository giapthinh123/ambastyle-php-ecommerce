<?php
include '../config/db.php';

// Xác nhận đơn hàng
if (isset($_POST['approve'])) {
    $order_id = $_POST['order_id'];
    
    $sql = "UPDATE orders SET order_status = 'Đang xử lý' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Tạo bản ghi trong shipping_status
        $sql_shipping = "INSERT INTO shipping_status (order_id, status) VALUES (?, 'Đang Chuẩn Bị Đơn Hàng')";
        $stmt_shipping = mysqli_prepare($conn, $sql_shipping);
        mysqli_stmt_bind_param($stmt_shipping, "i", $order_id);
        mysqli_stmt_execute($stmt_shipping);

        header("Location: ../admin/admin_order_manage.php?success=1");
        exit();
    } else {
        echo "Lỗi cập nhật đơn hàng.";
    }
}

// Hủy đơn hàng
if (isset($_POST['cancel'])) {
    $order_id = $_POST['order_id'];
    
    $sql = "UPDATE orders SET order_status = 'Hủy đơn' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/admin_order_manage.php?canceled=1");
        exit();
    } else {
        echo "Lỗi hủy đơn hàng.";
    }
}
?>
