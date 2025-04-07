<?php 
    //ini_set('session.gc_maxlifetime', 60);
    session_start();
    include 'db_connect.php';

    $response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra nếu người dùng đã đăng nhập
    if (!isset($_SESSION['user_id'])) {
        $response['status'] = 'error';
        $response['message'] = 'Please log in first';
        echo json_encode($response);
        exit();
    }

    $product_id = intval($_POST['id']); // ID của sản phẩm
    $quantity = intval($_POST['quantity']); // Số lượng sản phẩm
    $user_id = $_SESSION['user_id']; // ID của người dùng

    // Lấy giá sản phẩm
    $sql_product = "SELECT price FROM products WHERE category_id = ?";
    $stmt_product = $conn->prepare($sql_product);
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $stmt_product->bind_result($price);
    $stmt_product->fetch();
    $stmt_product->close();
    
    $total_price = $price * $quantity;

    // Thêm đơn hàng vào bảng orders
    $sql_order = "INSERT INTO orders (user_id, total_price, created_at) VALUES (?, ?, NOW())";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("id", $user_id, $total_price);
    
    if ($stmt_order->execute()) {
        $order_id = $conn->insert_id; // Lấy ID của đơn hàng vừa tạo

        $status = 'In cart';

        // Thêm chi tiết đơn hàng vào bảng order_details
        $sql_order_details = "INSERT INTO order_details (order_id, product_id, quantity, price,status) VALUES (?, ?, ?, ?,?)";
        $stmt_order_details = $conn->prepare($sql_order_details);
        $stmt_order_details->bind_param("iiids", $order_id, $product_id, $quantity, $price,$status);
        
        if ($stmt_order_details->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Product added to cart successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to add details to the order.';
        }
        $stmt_order_details->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to create order.';
    }
    $stmt_order->close();
    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

echo json_encode($response); 
    ?>