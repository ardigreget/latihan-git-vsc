<?php
// logout.php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Ganti dengan ID ekstensi Anda
$extension_id = 'abcdefghijklmnopqrstu1234567890'; // Ganti dengan ID sebenarnya
$origin = "chrome-extension://$extension_id";

header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Credentials: true");

// Hapus session dan cookie
session_destroy();
setcookie("username", "", time() - 3600, "/"); // Hapus cookie

echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
?>
