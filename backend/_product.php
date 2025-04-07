<?php
    include 'db_connect.php';
    session_start();

    // Prevent caching of this page
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $sql = "SELECT id, name, description, price, img, category_id FROM products";
    $result = $conn->query($sql);

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);

    $conn->close();
?>