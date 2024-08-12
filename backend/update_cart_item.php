<?php
include 'db_connect.php';
session_start();

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['quantity'])) {
        $id = $_POST['id'];
        $price= $_POST['price'];
        $quantity = intval($_POST['quantity']);

        // Kiểm tra và cập nhật số lượng
        $sql = "UPDATE order_details SET quantity = ? WHERE order_id = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $quantity, $id);

        $total_price=$quantity*$price;

        $sql1 = "UPDATE orders SET total_price = ? WHERE id = ? ";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param('ii', $total_price, $id);
        $stmt1->execute();
        
        if ($stmt->execute()) {
            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to update item.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Missing parameters.'];
    }
}

echo json_encode($response);
?>
