<?php
require_once 'config.php'; // Koneksi database

// Ambil token dari header
$token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
$browser_id = $_SERVER['HTTP_X_BROWSER_ID'] ?? null;

if ($token && $browser_id) {
    $query = $pdo->prepare("SELECT * FROM users WHERE token = :token");
    $query->execute(['token' => $token]);
    $user = $query->fetch();

    if ($user) {
        // Cek berapa banyak token yang digunakan oleh user
        $countQuery = $pdo->prepare("SELECT COUNT(*) as tokenCount FROM tokens WHERE user_id = :user_id");
        $countQuery->execute(['user_id' => $user['id']]);
        $tokenCount = $countQuery->fetch()['tokenCount'];

        echo json_encode([
            'success' => true,
            'loggedIn' => true,
            'username' => $user['username'],
            'tokenCount' => $tokenCount,
        ]);
    } else {
        echo json_encode(['success' => false, 'loggedIn' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Token tidak ditemukan.']);
}
?>
