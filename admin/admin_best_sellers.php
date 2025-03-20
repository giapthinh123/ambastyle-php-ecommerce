<?php
include '../config/db.php';
include '../includes/header.php';

// Truy vấn sản phẩm bán chạy nhất
$query = "SELECT p.id, p.name, p.image_url, SUM(od.quantity) AS total_sold
          FROM order_details od
          JOIN products p ON od.product_id = p.id
          GROUP BY p.id
          ORDER BY total_sold DESC
          LIMIT 10";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Sản phẩm bán chạy</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<div class="main-content">
    <h2>Sản phẩm bán chạy nhất</h2>
    <table border="1">
        <tr>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Tổng số lượng bán</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><img src="../<?= $row['image_url'] ?>" width="80" height="80"></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['total_sold'] ?> cái</td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
