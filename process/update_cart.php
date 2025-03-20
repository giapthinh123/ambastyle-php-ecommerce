<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Vui lòng đăng nhập!");
}

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    if (isset($_POST['increase'])) {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = ?";
    } elseif (isset($_POST['decrease'])) {
        $sql = "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = ?";
    } elseif (isset($_POST['size'])) {
        $size = $_POST['size'];
        $sql = "UPDATE cart SET size = ? WHERE id = ?";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (isset($_POST['size'])) {
        mysqli_stmt_bind_param($stmt, "si", $size, $cart_id);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $cart_id);
    }

    mysqli_stmt_execute($stmt);
}

header("Location: ../Pages/cart.php");
exit();
?>
