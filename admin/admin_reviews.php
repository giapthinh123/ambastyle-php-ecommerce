<?php
ob_start();
include '../config/db.php';
include '../includes/admin_header.php';

// Xóa đánh giá
if (isset($_POST['delete_review'])) {
    $review_id = intval($_POST['review_id']);

    // Xóa đánh giá
    $sql_delete_review = "DELETE FROM product_reviews WHERE id = ?";
    $stmt_review = $conn->prepare($sql_delete_review);
    $stmt_review->bind_param("i", $review_id);
    
    if ($stmt_review->execute()) {
        $_SESSION['success'] = "Đánh giá đã được xóa!";
    } else {
        $_SESSION['error'] = "Lỗi khi xóa đánh giá!";
    }
    $stmt_review->close();
    
    // Đảm bảo không có nội dung nào xuất ra trước khi redirect
    header("Location: ../admin/admin_reviews.php");
    exit();
}

// Xóa phản hồi
if (isset($_POST['delete_response'])) {
    $response_id = intval($_POST['response_id']);

    // Xóa phản hồi
    $sql_delete_response = "DELETE FROM review_responses WHERE id = ?";
    $stmt_response = $conn->prepare($sql_delete_response);
    $stmt_response->bind_param("i", $response_id);
    
    if ($stmt_response->execute()) {
        $_SESSION['success'] = "Phản hồi đã được xóa!";
    } else {
        $_SESSION['error'] = "Lỗi khi xóa phản hồi!";
    }
    $stmt_response->close();
    
    // Đảm bảo không có nội dung nào xuất ra trước khi redirect
    header("Location: ../admin/admin_reviews.php");
    exit();
}

// Lấy danh sách đánh giá kèm phản hồi
$query = "SELECT pr.id, u.full_name, p.name, pr.rating, pr.review, pr.created_at,
                 rr.id as response_id, rr.response, a.full_name AS admin_name
          FROM product_reviews pr
          JOIN users u ON pr.user_id = u.id
          JOIN products p ON pr.product_id = p.id
          LEFT JOIN review_responses rr ON pr.id = rr.review_id
          LEFT JOIN users a ON rr.admin_id = a.id
          ORDER BY pr.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đánh Giá</title>
    <style>
        /* Định dạng chung */
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .main-content {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px #e3eced;
            margin-top: 100px;
            background-color: #D2E5E9;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #000000;
        }

        table th {
            background-color: #293239;
            color: white;
            text-align: center;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .delete-btn {
            background-color: #293239;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color:rgba(23, 23, 21, 0.34);
        }

        .response-form textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .response-form button {
            background-color: #293239;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .response-form button:hover {
            background-color:rgb(65, 72, 78);
        }

        .fa-star {
            color: #ffc107;
        }

        .fa-star.filled {
            color: #ffc107;
        }
    </style>
    <script>
        function confirmDeleteReview() {
            return confirm("Bạn có chắc muốn xóa đánh giá này?");
        }
        function confirmDeleteResponse() {
            return confirm("Bạn có chắc muốn xóa phản hồi này?");
        }
    </script>
</head>
<body>
<div class="main-content">
    <h2>Quản lý đánh giá sản phẩm</h2>

    <?php if (isset($_SESSION['success'])) { ?>
        <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php } ?>
    <?php if (isset($_SESSION['error'])) { ?>
        <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Đánh giá</th>
                <th>Bình luận</th>
                <th>Phản hồi của Admin</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($review = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $review['id'] ?></td>
                    <td><?= htmlspecialchars($review['full_name']) ?></td>
                    <td><?= htmlspecialchars($review['name']) ?></td>
                    <td>
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <i class="fa fa-star <?= $i <= $review['rating'] ? 'filled' : '' ?>"></i>
                        <?php } ?>
                    </td>
                    <td><?= nl2br(htmlspecialchars($review['review'])) ?></td>
                    <td>
                        <?php if ($review['response']) { ?>
                            <p><strong>Quý Xốp:</strong> <?= nl2br(htmlspecialchars($review['response'])) ?></p>
                            <form method="POST" onsubmit="return confirmDeleteResponse();">
                                <input type="hidden" name="response_id" value="<?= $review['response_id'] ?>">
                                <button type="submit" name="delete_response" class="delete-btn">Xóa phản hồi</button>
                            </form>
                        <?php } else { ?>
                            <form method="POST" class="response-form">
                                <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                <textarea name="response" rows="2" required placeholder="Phản hồi của bạn..."></textarea>
                                <button type="submit">Gửi</button>
                            </form>
                        <?php } ?>
                    </td>
                    <td><?= date("d/m/Y H:i", strtotime($review['created_at'])) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirmDeleteReview();">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <button type="submit" name="delete_review" class="delete-btn">Xóa đánh giá</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php
include '../includes/footer.php';
ob_end_flush();
?>