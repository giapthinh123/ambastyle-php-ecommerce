<?php
include '../config/db.php';
include '../includes/header.php';

if (isset($_GET['query'])) {
    $search_query = trim($_GET['query']);
    $search_query = mysqli_real_escape_string($conn, $search_query); // Tránh SQL Injection

    // Truy vấn tìm kiếm sản phẩm theo tên
    $sql = "SELECT id, name, price, image_url FROM products WHERE name LIKE ?";
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
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="main-content">
<div class="search-results-page">
    <h2>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($search_query); ?>"</h2>
    <?php if (!empty($products)) { ?>
        <div class="product-list">
            <?php foreach ($products as $product) { ?>
                <div class="product-item">
                    <a href="../Pages/ProductDetail.php?id=<?php echo $product['id']; ?>">
                        <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</p>
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>Không tìm thấy sản phẩm nào!</p>
    <?php } ?>
</div>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>