<?php
// generate_token.php
require 'config.php';

// Atur header untuk CORS
header("Access-Control-Allow-Origin: chrome-extension://*");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Cek metode request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
    exit();
}

// Ambil data JSON dari body
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['password']) || !isset($data['browser_id'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit();
}

$username = trim($data['username']);
$password = $data['password'];
$browser_id = trim($data['browser_id']);

// Cari pengguna berdasarkan username
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Pengguna tidak ditemukan']);
    exit();
}

// Verifikasi kata sandi
if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Kata sandi salah']);
    exit();
}

// Cek jumlah token aktif dalam 1 jam terakhir
$stmt = $pdo->prepare('SELECT COUNT(*) as count FROM extension_tokens WHERE user_id = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)');
$stmt->execute([$user['id']]);
$count = $stmt->fetch()['count'];

if ($count >= 2) {
    echo json_encode(['success' => false, 'message' => 'Pengguna maksimal 2 browser']);
    exit();
}

// Cek apakah browser_id sudah digunakan
$stmt = $pdo->prepare('SELECT * FROM extension_tokens WHERE user_id = ? AND browser_id = ?');
$stmt->execute([$user['id'], $browser_id]);
$existing = $stmt->fetch();

if ($existing) {
    echo json_encode(['success' => false, 'message' => 'Ekstensi sudah digunakan di browser ini']);
    exit();
}

// Generate token unik
$token = bin2hex(random_bytes(32));

// Insert token
$stmt = $pdo->prepare('INSERT INTO extension_tokens (user_id, token, browser_id) VALUES (?, ?, ?)');
$stmt->execute([$user['id'], $token, $browser_id]);

echo json_encode(['success' => true, 'token' => $token]);
?>
