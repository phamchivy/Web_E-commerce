<?php 
include 'db_connect.php';
//require_once '/../vendor/autoload.php'; // Đường dẫn để nạp autoload.php
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT; // Nạp lớp JWT từ thư viện Firebase
use Dotenv\Dotenv;

// Đặt tiêu đề Content-Type để trả về JSON
header('Content-Type: application/json');

// Secret key từ file cấu hình hoặc biến môi trường
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra sự tồn tại của trường email và password
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Sử dụng prepared statements để tránh SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
        
            if (password_verify($password, $hashed_password)) {
                // Mật khẩu hợp lệ, đăng nhập thành công
                $secretKey = $_ENV['JWT_ADMIN_SECRET_KEY'];
                if (!$secretKey) {
                    die('Error: Secret key is empty or not found.');
                }
                $payload = [
                    'iss' => 'yourdomain.com',
                    'iat' => time(),
                    'exp' => time() + 3600, // Token có hiệu lực trong 1 giờ
                    'userId' => $row['id']
                ];

                // Tạo JWT
                $jwt = JWT::encode($payload, $secretKey, 'HS256');
                // Trả về phản hồi với token và URL chuyển hướng
                $response = [
                    'status' => 'success',
                    'token' => $jwt,
                    'redirect' => '../frontend/admin_order.html'
                ];
            } else {
                // Mật khẩu không hợp lệ
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid credentials!'
                ];
            }
        } else {
            // Không tìm thấy người dùng
            $response = [
                'status' => 'error',
                'message' => 'Invalid credentials!'
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
