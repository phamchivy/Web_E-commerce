<?php
include 'db_connect.php';
//ini_set('session.gc_maxlifetime', 60);
session_start();

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Xác thực token
    $stmt = $conn->prepare("SELECT email FROM password_reset_tokens WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();

        // Cập nhật mật khẩu mới
        $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $new_password_hashed, $email);
        $stmt->execute();
        $stmt->close();

        // Xóa token đã sử dụng
        $stmt = $conn->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();

        $response['status'] = 'success';
        $response['message'] = 'Your password has been reset successfully.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid or expired token.';
    }
    
    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>
