<?php
// check_login.php
session_start();
require 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Ambil header Authorization dan X-Browser-ID
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
$browser_id = isset($headers['X-Browser-ID']) ? $headers['X-Browser-ID'] : '';

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1];

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
        echo json_encode(['loggedIn' => false]);
    }
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
