<?php
    include 'db_connect.php';
    //ini_set('session.gc_maxlifetime', 60);
    session_start();

    require '../vendor/autoload.php'; // Điều chỉnh đường dẫn nếu cần

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    // Khởi tạo PHPMailer
    $mail = new PHPMailer(true);

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];

        // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $token = bin2hex(random_bytes(16)); // Tạo token ngẫu nhiên

            // Lưu token vào cơ sở dữ liệu cùng với thời gian hết hạn
            $stmt = $conn->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, NOW() + INTERVAL 1 HOUR)");

            $stmt->bind_param("ss", $email, $token);
            $stmt->execute();

            // Gửi email cho người dùng với liên kết đặt lại mật khẩu
            $reset_link = "http://localhost/PHP_PROJECT/frontend/reset_password.html?token=" . $token;
            $subject = "Password Reset Request";
            $message = "Click the following link to reset your password: " . $reset_link;
            $headers = "From: no-reply@example.com";
            //mail($email, $subject, $message, $headers);

            try {
                // Cấu hình máy chủ email
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Máy chủ SMTP của Gmail
                $mail->SMTPAuth   = true;
                $mail->Username   = 'chivy20033012@gmail.com'; // Thay đổi với địa chỉ email của bạn
                $mail->Password   = 'tkpv lyep kcrl mmsd'; // Thay đổi với mật khẩu ứng dụng của bạn
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
            
                // Người gửi và người nhận
                $mail->setFrom('your-email@gmail.com', 'Your Name');
                $mail->addAddress($email); // Thay đổi với địa chỉ email người nhận
            
                // Nội dung email
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;
                $mail->AltBody = "Click the following link to reset your password: $reset_link";
            
                // Gửi email
                $mail->send();
                $response['status'] = 'success';
                $response['message'] = 'A password reset link has been sent to your email.';
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Message could not be sent.';
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No account found with that email address.';
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request. Method used: ' . $_SERVER['REQUEST_METHOD'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
?>
