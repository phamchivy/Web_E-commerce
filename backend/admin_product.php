<?php
require_once 'db_connect.php'; // Kết nối cơ sở dữ liệu
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT; // Nạp lớp JWT từ thư viện Firebase
use Dotenv\Dotenv;
use Firebase\JWT\Key;

header("Content-Type: application/json; charset=UTF-8");

/*
Lấy Secret key từ .env
// Secret key để giải mã JWT (phải giống với key dùng để tạo JWT)
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$secret_key = $_ENV['JWT_ADMIN_SECRET_KEY'];
*/

// Lấy tiêu đề Authorization
$headers = apache_request_headers();
if (!isset($headers['Authorization'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["message" => "Authorization header missing"]);
    exit();
}

// Lấy JWT từ tiêu đề Authorization (Bearer token)
$authHeader = $headers['Authorization'];
$arr = explode(" ", $authHeader);
$jwt = $arr[1]; // Phần token

if ($jwt) {
    try {
        // Giải mã JWT
        //$decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        
        //Lấy user_id từ token
        //$user_id = $decoded->userId; // Giả sử trong payload của token có 'data->id'

        // Truy vấn thông tin đơn hàng của người dùng
        if($_SERVER["REQUEST_METHOD"] == "GET"){

        // Truy vấn thông tin sản phẩm
        $sql = "SELECT id, name, description, price, created_at FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        // Khởi tạo một mảng để lưu trữ kết quả
        $products = [];

        // Lặp qua kết quả và thêm vào mảng
        while ($row = $result->fetch_assoc()) {
              $products[] = $row; // Thêm từng hàng vào mảng
        }

        // Trả về dữ liệu JSON
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "products" => $products
        ]);
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){

    }
    } catch (Exception $e) {
        // Nếu token không hợp lệ hoặc hết hạn
        http_response_code(401); // Unauthorized
        echo json_encode([
            "message" => "Access denied. Invalid token.",
            "error" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400); // Bad request
    echo json_encode(["message" => "No token provided."]);
}

?>
