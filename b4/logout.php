<?php
// logout.php
session_start();
require 'config.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: chrome-extension://*"); // Ganti dengan ID ekstensi Anda
header("Access-Control-Allow-Credentials: true");

// Hapus session dan cookie
session_destroy();
setcookie("username", "", time() - 3600, "/"); // Hapus cookie

echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
?>
