<?php
include '../config/db.php';
include '../includes/admin_header.php';

// Lấy danh sách tài khoản
$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý Tài Khoản</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<div class="main-content">
    <h2>Quản lý Tài Khoản</h2>

    <!-- Form thêm tài khoản -->
    <form action="../process/process_account.php" method="POST">
        <input type="text" name="full_name" placeholder="Họ và tên" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <input type="text" name="phone" placeholder="Số điện thoại" required>
        <textarea name="address" placeholder="Địa chỉ"></textarea>
        <select name="role">
            <option value="customer">Khách hàng</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="add_account">Thêm tài khoản</button>
    </form>

    <!-- Danh sách tài khoản -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Họ và Tên</th>
            <th>Email</th>
            <th>Điện thoại</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= $row['role'] == 'admin' ? 'Admin' : 'Khách hàng' ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <form action="../process/process_account.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_account" onclick="return confirm('Xóa tài khoản này?');">Xóa</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
