<?php
include 'db_connect.php';
session_start();

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Xóa đơn hàng
        $sql = "DELETE FROM order_details WHERE order_id = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $sql1 = "DELETE FROM orders WHERE id = ? ";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param('i', $id);
            $stmt1->execute();
            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to delete item.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Missing parameters.'];
    }
}

echo json_encode($response);
?>
