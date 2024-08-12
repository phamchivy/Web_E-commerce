<?php
//ini_set('session.gc_maxlifetime', 60);
include 'db_connect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $_POST['query'];

    // Chống SQL Injection bằng cách sử dụng prepared statements
    $stmt = $conn->prepare("SELECT id, name, description, price, img, category_id FROM products WHERE name LIKE ?");
    $searchTerm = "%$query%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $response['status'] = 'success';
        $response['products'] = $products;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No products found.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
