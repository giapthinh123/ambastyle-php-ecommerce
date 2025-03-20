<?php
include '../config/db.php';

// Hàm upload ảnh
function uploadImage($file) {
    $targetDir = "../uploads/"; // Thư mục mặc định để lưu ảnh

    // Tạo thư mục nếu chưa có
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Xử lý tên file
    $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($file["name"]));
    $targetFile = $targetDir . $fileName;

    // Thực hiện upload
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return "uploads/" . $fileName; // Lưu đường dẫn ảnh vào database
    }
    return false;
}

// Thêm sản phẩm
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $discount_price = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : NULL;
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    
    // Upload ảnh chính
    $image_url = uploadImage($_FILES['image']);
    if (!$image_url) {
        die("Lỗi upload ảnh!");
    }

    // Thêm vào database
    $query = "INSERT INTO products (name, description, price, discount_price, stock, category_id, image_url) 
              VALUES ('$name', '$description', $price, " . ($discount_price ?: "NULL") . ", $stock, $category_id, '$image_url')";
    mysqli_query($conn, $query);
    $product_id = mysqli_insert_id($conn); // Lấy ID sản phẩm vừa thêm

    // Upload ảnh chi tiết
    if (!empty($_FILES['detail_images']['name'][0])) {
        foreach ($_FILES['detail_images']['name'] as $key => $value) {
            $file = [
                'name' => $_FILES['detail_images']['name'][$key],
                'type' => $_FILES['detail_images']['type'][$key],
                'tmp_name' => $_FILES['detail_images']['tmp_name'][$key],
                'error' => $_FILES['detail_images']['error'][$key],
                'size' => $_FILES['detail_images']['size'][$key]
            ];

            $detail_image_url = uploadImage($file);
            if ($detail_image_url) {
                $query = "INSERT INTO product_images (product_id, image) VALUES ($product_id, '$detail_image_url')";
                mysqli_query($conn, $query);
            }
        }
    }

    header("Location: ../admin/admin_products.php");
    exit();
}

// Sửa sản phẩm
if (isset($_POST['update_product'])) {
    $product_id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $discount_price = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : NULL;
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    $query = "UPDATE products SET name='$name', description='$description', price=$price, 
              discount_price=" . ($discount_price ?: "NULL") . ", stock=$stock, category_id=$category_id WHERE id=$product_id";

    // Cập nhật ảnh nếu có
    if (!empty($_FILES['image']['name'])) {
        $query = "SELECT image_url FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        
        // Xóa ảnh cũ nếu có
        if ($row && file_exists("../" . $row['image_url'])) {
            unlink("../" . $row['image_url']);
        }

        // Upload ảnh mới
        $image_url = uploadImage($_FILES['image']);
        if ($image_url) {
            $query = "UPDATE products SET name='$name', description='$description', price=$price, 
                      discount_price=" . ($discount_price ?: "NULL") . ", stock=$stock, category_id=$category_id, image_url='$image_url' WHERE id=$product_id";
        } else {
            header("Location: ../admin/admin_products.php?error=upload_failed");
            exit();
        }
    } else {
        $query = "UPDATE products SET name='$name', description='$description', price=$price, 
                  discount_price=" . ($discount_price ?: "NULL") . ", stock=$stock, category_id=$category_id WHERE id=$product_id";
    }

    mysqli_query($conn, $query);

    // Upload ảnh chi tiết nếu có
    if (!empty($_FILES['detail_images']['name'][0])) {
        // Xóa ảnh chi tiết cũ
        $query = "DELETE FROM product_images WHERE product_id = $product_id";
        mysqli_query($conn, $query);

        foreach ($_FILES['detail_images']['name'] as $key => $value) {
            $file = [
                'name' => $_FILES['detail_images']['name'][$key],
                'type' => $_FILES['detail_images']['type'][$key],
                'tmp_name' => $_FILES['detail_images']['tmp_name'][$key],
                'error' => $_FILES['detail_images']['error'][$key],
                'size' => $_FILES['detail_images']['size'][$key]
            ];

            $detail_image_url = uploadImage($file);
            if ($detail_image_url) {
                mysqli_query($conn, "INSERT INTO product_images (product_id, image) VALUES ($product_id, '$detail_image_url')");
            }
        }
    }

    header("Location: ../admin/admin_products.php?success=product_updated");
    exit();
}

// Xóa ảnh chi tiết
if (isset($_POST['delete_detail_image'])) {
    $image_id = intval($_POST['image_id']);

    // Lấy đường dẫn ảnh
    $query = "SELECT image FROM product_images WHERE id = $image_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row && file_exists("../" . $row['image'])) {
        unlink("../" . $row['image']); // Xóa file ảnh trên máy chủ
    }

    // Xóa khỏi database
    $query = "DELETE FROM product_images WHERE id = $image_id";
    mysqli_query($conn, $query);

    header("Location: ../admin/admin_products.php");
    exit();
}

// Xóa sản phẩm
if (isset($_POST['delete_product'])) {
    $product_id = intval($_POST['id']);

    // Xóa ảnh chính
    $query = "SELECT image_url FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    if ($row && file_exists("../" . $row['image_url'])) {
        unlink("../" . $row['image_url']);
    }

    // Xóa ảnh chi tiết
    $query = "SELECT image FROM product_images WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        if (file_exists("../" . $row['image'])) {
            unlink("../" . $row['image']);
        }
    }

    // Xóa dữ liệu trong bảng product_images
    $query = "DELETE FROM product_images WHERE product_id = $product_id";
    mysqli_query($conn, $query);

    // Xóa sản phẩm
    $query = "DELETE FROM products WHERE id = $product_id";
    mysqli_query($conn, $query);

    header("Location: ../admin/admin_products.php");
    exit();
}
?>