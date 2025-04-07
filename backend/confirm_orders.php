<?php
include 'db_connect.php';
session_start();
$response = array();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu không có session, có thể trả về thông báo lỗi
    $response = [
        'status' => 'error',
        'message' => 'User not logged in.'
    ];
    echo json_encode($response);
    exit; // Ngăn không cho thực hiện tiếp
}

// Lấy thông tin từ session
$user_id = $_SESSION['user_id']; // ID người dùng
$email = $_SESSION['user_email']; // Email người dùng

// Lấy thông tin từ POST request
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];

// Lưu thông tin đơn hàng vào cơ sở dữ liệu
$stmt = $conn->prepare("INSERT INTO submit_orders (user_id, name, address, phone, email, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("issss", $user_id, $name, $address, $phone, $email);

if ($stmt->execute()) {
    $response = [
        'status' => 'success',
        'message' => 'Submit order successfully'
    ];
    $status="Pending";
    $stmt1 = $conn->prepare("SELECT id FROM orders WHERE user_id = ?;");
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();
    $result = $stmt1->get_result(); // Sửa lại biến đây
    $order_ids = [];
    while ($row = $result->fetch_assoc()) {
        $order_ids[] = $row['id']; // Lưu từng id vào mảng
    }

    // Kiểm tra xem có ID nào không
    if (count($order_ids) > 0) {
        $placeholders = implode(',', array_fill(0, count($order_ids), '?'));
        $sql = "UPDATE order_details SET status = ? WHERE id IN ($placeholders)"; // Sửa tên bảng nếu cần
        $stmt2 = $conn->prepare($sql);
        
        // Tạo mảng tham số cho bind_param
        $params = array_merge([$status], $order_ids); // $status là giá trị trạng thái bạn muốn cập nhật
        $types = 's' . str_repeat('i', count($order_ids)); // 's' cho status, 'i' cho id

        $stmt2->bind_param($types, ...$params);
        
        if ($stmt2->execute()) {
            //echo "Cập nhật thành công!";
        } else {
            //echo "Lỗi khi cập nhật: " . $stmt2->error;
        }
    } else {
        //echo "Không tìm thấy đơn hàng nào để cập nhật.";
    }       
} else {
    $response = [
        'status' => 'error',
        'message' => 'Cannot submit order'
    ];
}
/*
// Chuẩn bị câu lệnh SQL
$stmt1 = $conn->prepare("SELECT id FROM orders WHERE user_id = ?");
$stmt1->bind_param("i", $user_id); // Giả sử user_id là kiểu integer

// Thực thi câu lệnh
$stmt1->execute();

// Lấy kết quả
$result = $stmt1->get_result();
$order_ids = [];

// Lặp qua kết quả và lưu vào mảng
while ($row = $result->fetch_assoc()) {
    $order_ids[] = $row['id'];
}


if (!empty($order_ids)) {
    // Chuyển đổi mảng thành chuỗi các ID cho câu lệnh SQL
    $id_string = implode(',', array_map('intval', $order_ids));

    // Chuẩn bị câu lệnh xóa
    $stmt2 = $conn->prepare("DELETE FROM order_details WHERE id IN ($id_string)");
    // Thực thi câu lệnh xóa 
    $stmt2->execute();
} else {
    //$response = ['status' => 'error', 'message' => 'No order detail IDs provided.'];
}

$stmt3 = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
$stmt3->bind_param("i", $user_id); // Giả sử user_id là kiểu integer

// Thực thi câu lệnh
$stmt3->execute();
*/
// Đóng kết nối
$stmt->close();
$stmt1->close();
$stmt2->close();
//$stmt3->close();
$conn->close();

// Trả về phản hồi
echo json_encode($response);
?>
