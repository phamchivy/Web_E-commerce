<?php
    include 'db_connect.php';
    //ini_set('session.gc_maxlifetime', 60);
    session_start();

    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'User not logged in']);
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



