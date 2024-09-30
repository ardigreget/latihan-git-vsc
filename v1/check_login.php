<?php
// check_login.php
require 'config.php';

// Atur header untuk CORS
header("Access-Control-Allow-Origin: chrome-extension://*"); // Ganti dengan ID ekstensi Anda
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Authorization");
header("Content-Type: application/json");

// Cek metode request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['loggedIn' => false, 'message' => 'Metode tidak diizinkan']);
    exit();
}

// Ambil header Authorization
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1];
} else {
    echo json_encode(['loggedIn' => false, 'message' => 'Token tidak ditemukan']);
    exit();
}

// Ambil header X-Browser-ID
$browser_id = isset($headers['X-Browser-ID']) ? trim($headers['X-Browser-ID']) : '';

if (empty($browser_id)) {
    echo json_encode(['loggedIn' => false, 'message' => 'Browser ID tidak ditemukan']);
    exit();
}

// Cek token dan browser_id dalam tabel extension_tokens dalam 1 jam terakhir
$stmt = $pdo->prepare('SELECT users.username, users.roles FROM extension_tokens 
                       JOIN users ON extension_tokens.user_id = users.id 
                       WHERE extension_tokens.token = ? AND extension_tokens.browser_id = ? AND extension_tokens.created_at >= (NOW() - INTERVAL 1 HOUR)');
$stmt->execute([$token, $browser_id]);
$user = $stmt->fetch();

if ($user) {
    echo json_encode([
        'loggedIn' => true,
        'username' => $user['username'],
        'roles' => $user['roles']
    ]);
} else {
    echo json_encode(['loggedIn' => false, 'message' => 'Token tidak valid atau telah kedaluwarsa']);
}
?>
