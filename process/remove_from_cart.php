<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Vui lòng đăng nhập!");
}

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    $delete_sql = "DELETE FROM cart WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, "i", $cart_id);
    mysqli_stmt_execute($stmt);
}

header("Location: ../Pages/cart.php");
exit();
?>
