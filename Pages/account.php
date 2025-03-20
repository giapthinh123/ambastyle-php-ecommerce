<?php
include '../config/db.php'; // Kết nối database
include '../includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem thông tin tài khoản!'); window.location.href='../Pages/login.php';</script>";
    exit();
}

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thông tin tài khoản</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="main-content">
    <div class="account-container">
        <h2>Thông tin tài khoản</h2>
        <p>Họ và tên: <?php echo $_SESSION['full_name']; ?></p>
        <p>Email: <?php echo $_SESSION['email']; ?></p>
        <p>Vai trò: <?php echo $role; ?></p>
        <a href="../Pages/logout.php">Đăng xuất</a>
        <?php if ($role == 'customer') { ?>
            <a href="../Pages/order_list.php" class="btn">Xem đơn hàng đã đặt</a>
        <?php } elseif ($role == 'admin') { ?>
            <a href="../admin/admin_order_manage.php" class="btn">Quản Lý Đơn Hàng</a>
            <a href="../admin/admin_products.php" class="btn">Quản Lý Sản Phẩm</a>
            <a href="../admin/admin_revenue.php" class="btn">Quản Lý Doanh Thu</a>
            <a href="../admin/admin_promotions.php" class="btn">Quản Lý Khuyến Mại</a>
            <a href="../admin/admin_accounts.php" class="btn">Quản Lý Tài Khoản</a>
            <a href="../admin/admin_best_sellers.php" class="btn">Sản Phẩm Bán Chạy Nhất</a>
            <a href="../admin/admin_reviews.php" class="btn">Quản Lý Góp Ý</a>
            <a href="../admin/admin_Shipping.php" class="btn">Quản Lý Vận Chuyển</a>
        <?php } ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>