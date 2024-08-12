    <?php
    //ini_set('session.gc_maxlifetime', 60);
    session_start(); // Bắt đầu session để sử dụng thông tin người dùng
    include 'db_connect.php'; // Kết nối cơ sở dữ liệu

    $response = array();

    // Kiểm tra nếu người dùng đã đăng nhập
    if (!isset($_SESSION['user_id'])) {
        $response['status'] = 'error';
        $response['message'] = 'Please log in first';
        echo json_encode($response);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Lấy danh sách đơn hàng của người dùng
    $sql = "SELECT o.id AS order_id, p.name, od.quantity, od.price, (od.quantity * od.price) AS total
            FROM orders o
            JOIN order_details od ON o.id = od.order_id
            JOIN products p ON od.product_id = p.id
            WHERE o.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = array();
    $total_cart_value = 0;

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_cart_value += $row['total'];
    }

    $response['status'] = 'success';
    $response['items'] = $cart_items;
    $response['total'] = $total_cart_value;

    $stmt->close();
    $conn->close();

    echo json_encode($response);
    ?>
