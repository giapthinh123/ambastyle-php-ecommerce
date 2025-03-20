<?php 
include '../config/db.php';
include '../includes/header.php';
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<script>alert('Sản phẩm không tồn tại!'); window.location.href='../Pages/Shop.php';</script>");
}

$product_id = intval($_GET['id']);

// Lấy thông tin sản phẩm
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("<script>alert('Sản phẩm không tồn tại!'); window.location.href='../Pages/Shop.php';</script>");
}

// Lấy danh sách ảnh sản phẩm
$sql_images = "SELECT * FROM product_images WHERE product_id = $product_id";
$result_images = mysqli_query($conn, $sql_images);

// Nếu không có ảnh nào trong `product_images`, dùng ảnh mặc định của sản phẩm
$default_image = isset($product['image']) ? $product['image'] : '../';
$images = [];

while ($img = mysqli_fetch_assoc($result_images)) {
    $images[] = $img['image'];
}

if (empty($images)) {
    $images[] = $default_image; // Nếu không có ảnh trong `product_images`, dùng ảnh chính của sản phẩm
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="product-detail">
        <!-- Khu vực hiển thị ảnh sản phẩm -->
        <div class="image-gallery">
            <div class="main-image">
                <img id="main-img" src="../<?= htmlspecialchars($images[0]) ?>">
            </div>
            <div class="thumbnails">
                <?php foreach ($images as $img) { ?>
                    <img src="../<?= htmlspecialchars($img) ?>" 
                         onclick="changeMainImage(this.src)" class="thumbnail">
                <?php } ?>
            </div>
        </div>

        <!-- Khu vực thông tin sản phẩm -->
        <div class="product-info">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p class="price"><?= number_format($product['price'], 0, ',', '.') ?>đ</p>
            <label for="size">Chọn Size:</label>
            <select id="size" name="size" required>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
            </select>
            <div class="buttons">
                <?php if ($product['stock'] > 0) { ?>
                    <form id="addToCartForm">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="size" id="selected-size">
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                        <button type="submit">Thêm vào giỏ hàng</button>
                    </form>
                    <form action="../Pages/Checkout.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="size" id="selected-size-buy">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="direct_buy" value="1"> <!-- Cờ để phân biệt mua ngay -->
                        <button type="submit">Mua Ngay</button>
                    </form>
                <?php } else { ?>
                    <p class="out-of-stock">Sản Phẩm Đã Hết Hàng</p>
                <?php } ?>
            </div>
            <div class="description">
                <h3>Mô Tả Sản Phẩm</h3>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
        </div>
    </div>
    <hr>
    
    <!-- PHẦN ĐÁNH GIÁ SẢN PHẨM -->
    <div class="product-reviews">
        <h3>Đánh Giá Sản Phẩm</h3>
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            // Kiểm tra khách hàng đã mua sản phẩm chưa
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT COUNT(*) 
                    FROM order_details od
                    JOIN orders o ON od.order_id = o.id
                    WHERE o.user_id = ? AND od.product_id = ?";
            
            $check_purchase = $conn->prepare($sql);
            $check_purchase->bind_param("ii", $user_id, $product_id);
            $check_purchase->execute();
            $check_purchase->bind_result($has_purchased);
            $check_purchase->fetch();
            $check_purchase->close();
    
            if ($has_purchased > 0) {
        ?>
                <form action="../process/submit_review.php" method="POST" class="review-form">
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    <label for="rating">Chọn Đánh Giá:</label>
                    <select name="rating" required>
                        <option value="5">⭐⭐⭐⭐⭐ - Rất tốt</option>
                        <option value="4">⭐⭐⭐⭐ - Tốt</option>
                        <option value="3">⭐⭐⭐ - Bình thường</option>
                        <option value="2">⭐⭐ - Tệ</option>
                        <option value="1">⭐ - Rất tệ</option>
                    </select>
                    <label for="review">Viết Nhận Xét:</label>
                    <textarea name="review" rows="3" required placeholder="Nhận xét của bạn..."></textarea>
                    <button type="submit">Gửi Đánh Giá</button>
                </form>
        <?php
            } else {
                echo "<p><i>Bạn cần mua sản phẩm này để có thể đánh giá.</i></p>";
            }
        } else {
            echo "<p><i>Vui lòng <a href='../Pages/Login.php'>đăng nhập</a> để đánh giá sản phẩm.</i></p>";
        }
        ?>
        <h3>Đánh giá từ khách hàng:</h3>
        <?php
        // Hiển thị đánh giá từ database
        $stmt = $conn->prepare("SELECT u.full_name, r.id as review_id, r.rating, r.review, r.created_at 
                                FROM product_reviews r 
                                JOIN users u ON r.user_id = u.id
                                WHERE r.product_id = ?
                                ORDER BY r.created_at DESC");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();  
    
        while ($review = $result->fetch_assoc()) {
            echo '<div class="review">';
            echo '<strong>' . htmlspecialchars($review['full_name']) . '</strong> ';
            echo str_repeat('⭐', $review['rating']);
            echo '<p>' . htmlspecialchars($review['review']) . '</p>';
            echo '<small>' . $review['created_at'] . '</small>';
            
            // Hiển thị phản hồi của admin
            $review_id = $review['review_id'];
            $response_stmt = $conn->prepare("SELECT rr.response, rr.created_at 
                                             FROM review_responses rr 
                                             WHERE rr.review_id = ?");
            $response_stmt->bind_param("i", $review_id);
            $response_stmt->execute();
            $response_result = $response_stmt->get_result();
            
            while ($response = $response_result->fetch_assoc()) {
                echo '<div class="response">';
                echo '<strong>Quý Xốp</strong> ';
                echo '<p>' . htmlspecialchars($response['response']) . '</p>';
                echo '<small>' . $response['created_at'] . '</small>';
                echo '</div>';
            }
            $response_stmt->close();
            
            // Form phản hồi cho admin
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            echo '<form action="../process/submit_response.php" method="POST" class="response-form">';
            echo '<input type="hidden" name="review_id" value="' . $review_id . '">';
    
            // Thêm product_id vào form
            if (isset($_GET['id'])) {
            echo '<input type="hidden" name="product_id" value="' . intval($_GET['id']) . '">';
            }
            echo '<label for="response">Phản hồi:</label>';
            echo '<textarea name="response" rows="2" required placeholder="Phản hồi của bạn..."></textarea>';
            echo '<button type="submit">Gửi phản hồi</button>';
            echo '</form>';
           }
            
            echo '</div>';
        }
        $stmt->close();
        ?>
    </div>
  <hr>
    <!-- Phần sản phẩm tương tự -->
    <div class="related-products">
        <h3>OTHERS HAVE VIEWED</h3>
        <div class="product-list">
            <?php
            // Lấy danh sách sản phẩm tương tự
            $related_sql = "SELECT * FROM products WHERE category_id = {$product['category_id']} AND id != $product_id LIMIT 4";
            $related_result = mysqli_query($conn, $related_sql);
            while ($related_product = mysqli_fetch_assoc($related_result)) {
            ?>
                <div class="product-item">
                    <a href="../Pages/productDetail.php?id=<?= $related_product['id'] ?>">
                        <img src="../<?= htmlspecialchars($related_product['image_url']) ?>" alt="<?= htmlspecialchars($related_product['name']) ?>">
                    </a>
                    <h3><?= htmlspecialchars($related_product['name']) ?></h3>
                    <p><?= number_format($related_product['price'], 0, ',', '.') ?>đ</p>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        function changeMainImage(src) {
            document.getElementById("main-img").src = src;
        }

        // Lưu size được chọn vào input form
        document.getElementById("size").addEventListener("change", function() {
            document.getElementById("selected-size").value = this.value;
            document.getElementById("selected-size-buy").value = this.value;
        });

        $(document).ready(function() {
            $("#addToCartForm").submit(function(event) {
                event.preventDefault(); // Ngăn chặn form tải lại trang

                $.ajax({
                    url: "../process/add_to_cart.php", // Đường dẫn file xử lý
                    type: "POST",
                    data: $(this).serialize(), // Lấy dữ liệu từ form
                    dataType: "json", // Định dạng JSON
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Thành công!",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            // Cập nhật số lượng sản phẩm trong giỏ hàng
                            var cartBadge = document.querySelector(".cart-badge");
                            if (cartBadge) {
                                cartBadge.textContent = response.cart_count;
                            } else {
                                var cartIcon = document.querySelector(".cart-icon");
                                var span = document.createElement("span");
                                span.className = "cart-badge";
                                span.textContent = response.cart_count;
                                cartIcon.appendChild(span);
                            }
                        } else {
                            Swal.fire({
                                title: "Lỗi!",
                                text: response.message,
                                icon: "error"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Lỗi!",
                            text: "Đã xảy ra lỗi khi thêm vào giỏ hàng.",
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>