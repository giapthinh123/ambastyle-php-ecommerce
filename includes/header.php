<?php
    include '../config/db.php';  // Kết nối database
    session_start();
// Lấy 10 sản phẩm mới nhất
$sql_new_products = "SELECT id, name, image_url FROM products ORDER BY created_at DESC LIMIT 10";
$result_new_products = mysqli_query($conn, $sql_new_products);

// Lấy 5 sản phẩm bán chạy nhất
$sql_best_sellers = "SELECT p.id, p.name, p.image_url, SUM(od.quantity) AS total_sold 
                     FROM order_details od
                     JOIN products p ON od.product_id = p.id
                     GROUP BY p.id
                     ORDER BY total_sold DESC 
                     LIMIT 5";
$result_best_sellers = mysqli_query($conn, $sql_best_sellers);
    // Lấy danh sách danh mục từ bảng categories
    $sql_categories = "SELECT * FROM categories";
    $result_categories = mysqli_query($conn, $sql_categories);

    // Kiểm tra nếu người dùng đã đăng nhập
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Truy vấn lấy tổng số lượng sản phẩm trong giỏ hàng
    $sql = "SELECT SUM(quantity) as total_items FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $cart_count = $row['total_items'] ?? 0; // Nếu không có sản phẩm nào thì mặc định là 0

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
<div class="promo-bar">
    <a href="../Pages/sale_products.php" class="promo-text">FREE DELIVERY FOR ORDER > 1.000.000 VND
    </a>
</div>
<header>
    <div class="container">
        <a href="index.php">
            <img src="../images/logo.jpg" alt="Fashion Store Logo" class="logo">
        </a>
        <nav>
            <ul>
                <li class="dropdown">
                    <a href="#" id="collection-link">COLLECTION</a>
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="Shop.php">Tất cả</a></li>
                            <?php while ($row = mysqli_fetch_assoc($result_categories)) { ?>
                                <li><a href="Shop.php?category=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></li>
                            <?php } ?>
                        </ul>   
                    </div>
                </li>
                <li><a href="#" id="story-link">STORIES</a></li>
                <li><a href="#" id="about-link">ABOUT</a></li>
            </ul> 
        </nav>
        <div class="header-right">
            <form action="../Pages/search.php" method="get" class="search-form">
                <input type="text" name="query" placeholder="Tìm kiếm sản phẩm...">
                <button type="submit">
                    <img src="../images/ico-search2x.svg" alt="">
                </button>
                <div id="search-results" class="search-results"></div>
            </form>
            <a href="../Pages/cart.php" class="cart-icon">
                         <img src="../images/Cart.png" alt=""><?php if ($cart_count > 0) { ?>
                        <span class="cart-badge"><?= $cart_count ?></span>
                 <?php } ?>
            </a>
            <div class="account-icon">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <div class="dropdown-content">
                            <a href="account.php"><img src="../images/Account.png" alt="Tài khoản"></a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php"><img src="../images/Account.png" alt="Tài khoản"></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
<!-- ABOUT SECTION ẨN MẶC ĐỊNH -->
<div id="about-section" class="about-content">
    <div class="about-text">
        <!-- Nội dung About -->
        <p><strong>Ambastyle creates a New Aesthetic</strong> by accessing technical fabrics, durable materials, experimental knitting techniques, and chemical dyeing treatments. From our perspective, traditional streetwear is now renewed by deconstruction through raw cuts, hand distressing, combining avant-garde garments and asymmetrical details.</p>
        <p>Design language of brand represents a POV on global youth subcultures. Sometimes an opinion, a statement, even an emotion, a blurry memory, sometimes an imagination of parallel dimension.</p>
        <p>Our seasonal collections push the boundaries in creativity, also balance the timeless aesthetics and honoring futuristic values. Goldie expresses the chaos and contradictions of society standards, provoking more questions than answers.</p>
        <p><strong>We are an unexpected concept proudly created by Vietnamese people.</strong></p>
        <hr>
        <p><strong>Ambastyle  hướng đến việc sáng tạo tính Thẩm Mĩ Mới</strong> bằng việc tiếp cận những chất liệu tiên phong với độ bền cao, các kỹ thuật dệt thử nghiệm, phương pháp xử lí nhuộm hoá chất. Trang phục đường phố quen thuộc dưới góc nhìn của thương hiệu được làm mới bằng việc giải cấu trúc thông qua những đường cắt thô, tạo hình rách thủ công, kết hợp nhiều chất liệu và chi tiết bất đối xứng.</p>
        <p>Ngôn ngữ thiết kế của Ambastyle  thể hiện góc nhìn cá nhân về những văn hoá đặc trưng của người trẻ. Đôi khi là quan điểm, là tuyên ngôn, 1 trạng thái cảm xúc, ký ức mơ hồ, hoặc thậm chí là sự tưởng tượng về 1 hình thái song song.</p>
        <p>Các bộ sưu tập theo mùa ngoài việc phá vỡ những giới hạn về sáng tạo, song vẫn luôn cân bằng giữa tính thẩm mĩ truyền thống và tôn vinh những giá trị đương đại. Cách tiếp cận thời trang của Goldie nói lên sự hỗn loạn và mâu thuẫn về những tiêu chuẩn trong xã hội, khơi gợi nhiều câu hỏi hơn là những câu trả lời.</p>
        <p><strong>Ambastyle là 1 hình thái vô định được tự hào tạo nên từ những người Việt.</strong></p>
        <hr>
        <h3>📍 STORE LOCATOR:</h3>
        <ul>
            <li><strong>Hanoi:</strong>
                <ul>
                    <li>360 Pho Hue</li>
                    <li>15 Ho Dac Di</li>
                </ul>
            </li>
            <li><strong>Saigon:</strong> @thenewplayground 26 Ly Tu Trong, District 1</li>
            <li><strong>Japan:</strong> <a href="https://www.sixty-percent.com/en/collections/goldie" target="_blank">www.sixty-percent.com/en/collections/goldie</a></li>
        </ul>
        <h3>📞 Contact:</h3>
        <p><strong>Hotline:</strong> 0985 032 589</p>
        <p><strong>Email:</strong> <a href="mailto:info@goldievietnam.com">info@goldievietnam.com</a></p>
        <li><a href="#"><img src="../images/anhicon1.png" alt="Facebook"></a></li>
        <li><a href="#"><img src="../images/anhicon2.png" alt="Instagram"></a></li>
    </div>
</div>
<!-- STORY SECTION ẨN MẶC ĐỊNH -->
<div id="story-section" class="story-content">
    <h2>✨ What's New? ✨</h2>

    <h3>🆕 10 Sản Phẩm Mới Nhất</h3>
    <div class="new-products">
        <?php while ($row = mysqli_fetch_assoc($result_new_products)) { ?>
            <a href="../Pages/productDetail.php?id=<?= $row['id'] ?>" class="new-product-item">
                <img src="../<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" title="<?= $row['name'] ?>">
            </a>
        <?php } ?>
    </div>

    <h3>🔥 5 Sản Phẩm Bán Chạy Nhất</h3>
    <div class="best-sellers">
        <?php while ($row = mysqli_fetch_assoc($result_best_sellers)) { ?>
            <a href="../Pages/productDetail.php?id=<?= $row['id'] ?>" class="best-seller-item">
                <img src="../<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" title="<?= $row['name'] ?>">
            </a>
        <?php } ?>
    </div>
</div>

<script src="../js/script.js"></script>
</body>
</html>