<?php
include '../config/db.php';

if (isset($_GET['query'])) {
    $search_query = trim($_GET['query']);
    $search_query = mysqli_real_escape_string($conn, $search_query); // Tránh SQL Injection

    // Truy vấn tìm kiếm sản phẩm theo tên
    $sql = "SELECT id, name, image_url FROM products WHERE name LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $search_param = "%{$search_query}%";
    mysqli_stmt_bind_param($stmt, "s", $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    mysqli_stmt_close($stmt);
    echo json_encode($products);
}
?>