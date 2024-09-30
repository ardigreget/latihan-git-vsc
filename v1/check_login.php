<?php
// check_login.php
session_start();

// Atur header untuk CORS
header("Access-Control-Allow-Origin: chrome-extension://*");
header("Content-Type: application/json");

// Cek apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'username' => $_SESSION['username'],
        'roles' => $_SESSION['roles']
    ]);
} else {
    echo json_encode([
        'loggedIn' => false
    ]);
}
?>
