<?php
// generate_token.php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$browser_id = isset($_GET['browser_id']) ? $_GET['browser_id'] : '';

if (!$browser_id) {
    echo json_encode(['success' => false, 'message' => 'Browser ID not provided']);
    exit();
}

// Cek jumlah token aktif dalam 1 jam terakhir
$stmt = $pdo->prepare('SELECT COUNT(*) as count FROM extension_tokens WHERE user_id = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)');
$stmt->execute([$user_id]);
$count = $stmt->fetch()['count'];

if ($count >= 2) {
    echo json_encode(['success' => false, 'message' => 'Pengguna maksimal 2 browser']);
    exit();
}

// Generate token unik
$token = bin2hex(random_bytes(16));

// Insert token
$stmt = $pdo->prepare('INSERT INTO extension_tokens (user_id, token, browser_id) VALUES (?, ?, ?)');
$stmt->execute([$user_id, $token, $browser_id]);

echo json_encode(['success' => true, 'token' => $token]);
?>
