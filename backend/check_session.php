<?php
session_start();

// Kiểm tra xem session 'user_id' đã được thiết lập hay chưa (thay 'user_id' bằng key của session mà bạn dùng để lưu thông tin người dùng)
if (isset($_SESSION['user_id'])) {
    // Nếu có phiên, trả về phản hồi JSON với trạng thái thành công
    echo json_encode(['success' => true]);
} else {
    // Nếu không có phiên, trả về phản hồi JSON với thông báo lỗi
    echo json_encode(['error' => 'Bạn chưa đăng nhập.']);
}
?>
