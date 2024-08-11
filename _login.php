<?php 
include 'db_connect.php';
session_start();

// Đặt tiêu đề Content-Type để trả về JSON
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra sự tồn tại của trường email và password
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql_check = "SELECT id, password FROM users WHERE email = '$email'";
        $result = $conn->query($sql_check);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
        
            if (password_verify($password, $hashed_password)) {
                // Mật khẩu hợp lệ, đăng nhập thành công
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_email'] = $email;
                $response = [
                    'status' => 'success',
                    'redirect' => '../frontend/product.html'
                ];
            } else {
                // Mật khẩu không hợp lệ
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid password!'
                ];
            }
        } else {
            // Không tìm thấy người dùng
            $response = [
                'status' => 'error',
                'message' => 'No user found with this email.'
            ];
        }
    } else {
        // Nếu email hoặc password không được cung cấp
        $response = [
            'status' => 'error',
            'message' => 'Email or password not provided!'
        ];
    }
}

// Trả về phản hồi JSON
echo json_encode($response);
?>
