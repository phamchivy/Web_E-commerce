<?php
//ini_set('session.gc_maxlifetime', 60);
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Kiểm tra hai mật khẩu có khớp nhau không
    if ($password !== $password_confirm) {
        $error_message = "Error: Passwords do not match.";
        // Chuyển hướng người dùng về trang form.php với thông báo lỗi
        header("Location: ../frontend/signup.html?error=" . urlencode($error_message));
        exit();
    }
    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $created_at = date('Y-m-d H:i:s');

    // Tạo câu lệnh SQL để chèn dữ liệu vào cơ sở dữ liệu
    $sql = "INSERT INTO users (email, password,created_at) VALUES ('$email', '$hashed_password','$created_at')";
    
    // Thực thi câu lệnh SQL
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        header("Location: ../frontend/product.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        header("Location: ../frontend/signup.html?error=" . urlencode($error_message));
        exit();
    }
}

?>
