<?php
// logout.php
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

// Hapus session dan cookie
session_destroy();
setcookie("username", "", time() - 3600, "/"); // Hapus cookie

echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
?>
