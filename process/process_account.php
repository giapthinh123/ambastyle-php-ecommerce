<?php
include '../config/db.php';

// Thêm tài khoản
if (isset($_POST['add_account'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role = $_POST['role'];

    $query = "INSERT INTO users (full_name, email, password, phone, address, role) 
              VALUES ('$full_name', '$email', '$password', '$phone', '$address', '$role')";
    mysqli_query($conn, $query);
    
    header("Location: ../admin/admin_accounts.php");
    exit();
}

// Xóa tài khoản
if (isset($_POST['delete_account'])) {
    $id = intval($_POST['id']);

    $query = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $query);

    header("Location: ../admin/admin_accounts.php");
    exit();
}
?>
