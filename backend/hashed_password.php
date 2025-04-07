<?php
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); 
$dotenv->load();

// Bây giờ bạn có thể sử dụng biến ADMIN_PASSWORD

$password = getenv('ADMIN_PASSWORD'); // Thay đổi mật khẩu ở đây
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>
<?php
// Tạo secret key ngẫu nhiên
$secretKey = bin2hex(random_bytes(32));

// In ra secret key để sao chép vào file .env
echo "\nSecret Key: " . $secretKey;
?>
