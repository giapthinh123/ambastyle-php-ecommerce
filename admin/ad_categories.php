<?php
include '../config/db.php';
include '../includes/admin_header.php';

// Lấy danh sách danh mục
$query = "SELECT * FROM categories ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý Danh Mục</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<div class="main-content">
    <h2>Quản lý Danh Mục</h2>
    
    <!-- Form thêm danh mục -->
    <form action="../process/process_category.php" method="POST">
        <input type="text" name="name" placeholder="Tên danh mục" required>
        <button type="submit" name="add_category">Thêm danh mục</button>
    </form>

    <!-- Danh sách danh mục -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <form action="../process/process_category.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_category" onclick="return confirm('Xóa danh mục này?');">Xóa</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
