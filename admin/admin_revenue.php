<?php
include '../config/db.php';
include '../includes/admin_header.php';

// Xử lý lọc theo tháng và năm
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$query = "SELECT * FROM revenue WHERE MONTH(revenue_date) = $month AND YEAR(revenue_date) = $year ORDER BY revenue_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý Doanh Thu</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<div class="main-content">
    <h2>Quản lý Doanh Thu</h2>

    <!-- Bộ lọc tháng & năm -->
    <form method="GET">
        <label for="month">Chọn tháng:</label>
        <select name="month">
            <?php for ($m = 1; $m <= 12; $m++) { ?>
                <option value="<?= $m ?>" <?= ($m == $month) ? 'selected' : '' ?>>Tháng <?= $m ?></option>
            <?php } ?>
        </select>

        <label for="year">Chọn năm:</label>
        <select name="year">
            <?php for ($y = 2020; $y <= date('Y'); $y++) { ?>
                <option value="<?= $y ?>" <?= ($y == $year) ? 'selected' : '' ?>>Năm <?= $y ?></option>
            <?php } ?>
        </select>

        <button type="submit">Lọc</button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ngày</th>
            <th>Tổng Đơn Hàng</th>
            <th>Tổng Doanh Thu (VNĐ)</th>
            <th>Ngày tạo</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['revenue_date'] ?></td>
            <td><?= $row['total_orders'] ?></td>
            <td><?= number_format($row['total_revenue'], 2) ?> VNĐ</td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
