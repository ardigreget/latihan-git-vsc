<?php
// api.php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Menangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: $base_url");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");
    exit(0);
}

// Set CORS headers
header("Access-Control-Allow-Origin: $base_url");
header("Access-Control-Allow-Credentials: true");

if(isset($_COOKIE['username'])) {
    echo json_encode(["logged_in" => true, "username" => $_COOKIE['username']]);
} else {
    echo json_encode(["logged_in" => false]);
}
?>
