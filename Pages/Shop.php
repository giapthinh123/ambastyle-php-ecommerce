<?php 
    include '../config/db.php'; // Kết nối database
    include '../includes/header.php'; 
    
    // Lấy category_id từ URL, nếu không có thì mặc định là NULL
    $category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

    // Nếu có category_id, kiểm tra xem danh mục có tồn tại không
    if ($category_id) {
        $categoryQuery = "SELECT name FROM categories WHERE id = $category_id";
        $categoryResult = mysqli_query($conn, $categoryQuery);
        if (mysqli_num_rows($categoryResult) > 0) {
            $category = mysqli_fetch_assoc($categoryResult)['name'];
        } else {
            die("<script>alert('Danh mục không tồn tại!'); window.location.href='products.php';</script>");
        }
        $sql = "SELECT * FROM products WHERE category_id = $category_id";
    } else {
        $category = "Tất cả sản phẩm";
        $sql = "SELECT * FROM products"; // Hiển thị tất cả sản phẩm nếu không có danh mục cụ thể
    }

    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Shop - <?= htmlspecialchars($category) ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="main-content">
        <h2><?= htmlspecialchars($category) ?></h2>
        <div class="product-list">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="product-item">
                        <a href="productDetail.php?id=<?= $row['id'] ?>">
                            <img src="../<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" 
                                 onerror="this.onerror=null; this.src='../images/default.png'">
                        </a>
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p>Giá: <?= number_format($row['price'], 0, ',', '.') ?>đ</p>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="no-products">Không có sản phẩm nào.</p>
            <?php } ?>
        </div>
    </div>
    <div class="separator"></div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
