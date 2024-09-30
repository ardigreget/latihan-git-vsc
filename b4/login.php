<?php
// login.php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Menangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: $base_url");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");
    exit(0);
}

// Set CORS headers
header("Access-Control-Allow-Origin: $base_url");
header("Access-Control-Allow-Credentials: true");

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Mendapatkan data JSON dari permintaan
    $data = json_decode(file_get_contents("php://input"), true);
    
    $user = $data['username'] ?? '';
    $pass = $data['password'] ?? '';
    
    // Prepared statement untuk mencegah SQL Injection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $user]);
    $user_data = $stmt->fetch();
    
    if ($user_data) {
        if (password_verify($pass, $user_data['password'])) {
            // Set session dan cookie
            $_SESSION['username'] = $user;
            setcookie("username", $user, time() + (86400 * 30), "/"); // Cookie berlaku 30 hari
            
            echo json_encode(["status" => "success", "message" => "Login successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
