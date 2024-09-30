<?php
include 'db.php';
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    // Jika user sudah login, kirim respons positif
    echo json_encode([
        'status' => 'success',
        'message' => 'User is logged in'
    ]);
} else {
    // Jika user belum login, kirim respons negatif
    echo json_encode([
        'status' => 'error',
        'message' => 'User is not logged in'
    ]);
}
?>
