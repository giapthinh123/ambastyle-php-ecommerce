<?php
include '../config/db.php';

// Thêm danh mục
if (isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $query = "INSERT INTO categories (name) VALUES ('$name')";
    mysqli_query($conn, $query);
    header("Location: ../admin/ad_categories.php");
    exit();
}

// Xóa danh mục
if (isset($_POST['delete_category'])) {
    $id = intval($_POST['id']);
    $query = "DELETE FROM categories WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: ../admin/ad_categories.php");
    exit();
}
?>
