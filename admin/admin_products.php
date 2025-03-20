<?php
include '../config/db.php';
include '../includes/admin_header.php';

// Lấy danh sách sản phẩm
$query = "SELECT p.*, c.name AS category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);

// Lấy danh sách danh mục để chọn khi thêm sản phẩm
$categoryQuery = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý Sản Phẩm</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<div class="main-content">
    <h2>Quản lý Sản Phẩm</h2>
    <!-- Form thêm sản phẩm -->
    <form action="../process/process_product.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Tên sản phẩm" required>
        <textarea name="description" placeholder="Mô tả"></textarea>
        <input type="number" name="price" placeholder="Giá" required>
        <input type="number" name="discount_price" placeholder="Giá sau khi giảm (nếu có)">
        <input type="number" name="stock" placeholder="Tồn kho" required>
        <select name="category_id">
            <option value="">Chọn danh mục</option>
            <?php while ($cat = mysqli_fetch_assoc($categoryResult)) { ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php } ?>
        </select>
        
        <!-- Ảnh chính -->
        <label>Ảnh chính:</label>
        <input type="file" name="image" required>

        <!-- Ảnh chi tiết -->
        <label>Ảnh chi tiết (có thể chọn nhiều ảnh):</label>
        <input type="file" name="detail_images[]" multiple>

        <button type="submit" name="add_product">Thêm sản phẩm</button>
    </form>
    <!-- Form sửa sản phẩm -->
    <div id="editForm" style="display: none;">
        <h2>Sửa sản phẩm</h2>
        <form action="../process/process_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="name" id="edit_name" required>
            <textarea name="description" id="edit_description"></textarea>
            <input type="number" name="price" id="edit_price" required>
            <input type="number" name="discount_price" id="edit_discount_price" placeholder="Giá khuyến mãi">
            <input type="number" name="stock" id="edit_stock" required>
            <select name="category_id" id="edit_category">
                <option value="">Chọn danh mục</option>
                <?php
                $categoryQuery = "SELECT * FROM categories";
                $categoryResult = mysqli_query($conn, $categoryQuery);
                while ($cat = mysqli_fetch_assoc($categoryResult)) { ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php } ?>
            </select>

            <p>Ảnh hiện tại:</p>
            <img id="edit_image" width="100"><br>
            <input type="file" name="image">

            <!-- Ảnh chi tiết -->
            <label>Ảnh chi tiết (có thể chọn nhiều ảnh):</label>
            <input type="file" name="detail_images[]" multiple> 
            
            <button type="submit" name="update_product">Cập nhật</button>
            <button type="button" onclick="document.getElementById('editForm').style.display='none'">Hủy</button>
        </form>
    </div>
    <!-- Danh sách sản phẩm -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Giá sau khi giảm</th>
            <th>Tồn kho</th>
            <th>Mô tả</th>
            <th class="detail-images-column">Ảnh chi tiết</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td>
                <img src="../<?= htmlspecialchars($row['image_url']) ?>" width="50">
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= number_format($row['price'], 0, ',', '.') ?>đ</td>
            <td><?= $row['discount_price'] ? number_format($row['discount_price'], 0, ',', '.') . 'đ' : '-' ?></td>
            <td><?= $row['stock'] ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td class="detail-images-column">
                <div class="product-thumbnails">
                    <?php
                    $product_id = $row['id'];
                    $imageQuery = "SELECT image FROM product_images WHERE product_id = $product_id";
                    $imageResult = mysqli_query($conn, $imageQuery);
                    while ($image = mysqli_fetch_assoc($imageResult)) {
                        echo '<img src="../' . htmlspecialchars($image['image']) . '" width="30">';
                    }
                    ?>
                </div>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <!-- Nút sửa: gọi JavaScript -->
                <button name="edit_product" onclick="editProduct(<?= $row['id'] ?>, '<?= addslashes($row['name']) ?>', '<?= addslashes($row['description']) ?>', <?= $row['price'] ?>, <?= $row['stock'] ?>, <?= $row['category_id'] ?>, '<?= addslashes($row['image_url']) ?>')">Sửa</button>
                
                <!-- Nút xóa -->
                <form action="../process/process_product.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_product" onclick="return confirm('Xóa sản phẩm này?');">Xóa</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<script>
function editProduct(id, name, description, price, discount_price, stock, category_id, image) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
    document.getElementById("edit_description").value = description;
    document.getElementById("edit_price").value = price;
    document.getElementById("edit_discount_price").value = discount_price;
    document.getElementById("edit_stock").value = stock;
    document.getElementById("edit_category").value = category_id;
    document.getElementById("edit_image").src = "../" + image;
    document.getElementById("editForm").style.display = "block";
}

</script>
</body>
</html>

<?php include '../includes/footer.php'; ?>