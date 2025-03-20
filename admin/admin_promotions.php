<?php
include '../config/db.php';
include '../includes/admin_header.php';

// Lấy danh sách khuyến mãi
$query = "SELECT * FROM promotions ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý Khuyến Mại</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<div class="main-content">
    <h2>Quản lý Khuyến Mại</h2>

    <!-- Form thêm khuyến mãi -->
    <form action="../process/process_promotion.php" method="POST">
        <input type="text" name="code" placeholder="Mã khuyến mãi" required>
        <input type="number" name="discount_percentage" step="0.01" min="0" max="100" placeholder="Phần trăm giảm giá" required>
        <input type="date" name="start_date" required>
        <input type="date" name="end_date" required>
        <button type="submit" name="add_promotion">Thêm khuyến mãi</button>
    </form>

    <!-- Danh sách khuyến mãi -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Mã</th>
            <th>Giảm giá (%)</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['code']) ?></td>
            <td><?= $row['discount_percentage'] ?>%</td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <form action="../process/process_promotion.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_promotion" onclick="return confirm('Xóa khuyến mãi này?');">Xóa</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
