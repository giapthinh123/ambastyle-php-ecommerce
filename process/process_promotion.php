<?php
include '../config/db.php';

// Thêm khuyến mãi
if (isset($_POST['add_promotion'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $discount_percentage = floatval($_POST['discount_percentage']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $query = "INSERT INTO promotions (code, discount_percentage, start_date, end_date) 
              VALUES ('$code', $discount_percentage, '$start_date', '$end_date')";
    mysqli_query($conn, $query);

    header("Location: ../admin/admin_promotions.php");
    exit();
}

// Xóa khuyến mãi
if (isset($_POST['delete_promotion'])) {
    $id = intval($_POST['id']);

    $query = "DELETE FROM promotions WHERE id = $id";
    mysqli_query($conn, $query);

    header("Location: ../admin/admin_promotions.php");
    exit();
}
?>
