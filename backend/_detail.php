<?php
//ini_set('session.gc_maxlifetime', 60);
session_start();
include 'db_connect.php'; // Đảm bảo rằng đường dẫn chính xác

// Kiểm tra nếu có tham số `id` trong URL
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No product ID provided']);
    exit();
}

$product_id = intval($_GET['id']); // Lấy ID sản phẩm từ URL

// Truy vấn cơ sở dữ liệu
$sql = "SELECT name, description, price, img FROM products WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode($product); // Trả về thông tin sản phẩm dưới dạng JSON
} else {
    echo json_encode(['error' => 'Product not found']);
}

$stmt->close();
$conn->close();
?>
