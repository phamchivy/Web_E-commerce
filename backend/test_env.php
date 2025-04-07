<?php

require __DIR__ . '/../vendor/autoload.php'; // Tải autoload của Composer

use Dotenv\Dotenv; // Sử dụng namespace Dotenv

// Tạo một instance của Dotenv và chỉ định đường dẫn đến file .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../'); // Đường dẫn đến file .env
$dotenv->load(); // Tải các biến môi trường từ file .env

// Kiểm tra và in ra các biến môi trường
echo "DB_HOST: " . $_ENV['DB_HOST'] . PHP_EOL;
echo "DB_USER: " . $_ENV['DB_USER'] . PHP_EOL;
echo "DB_PASS: " . $_ENV['DB_PASS'] . PHP_EOL;

?>