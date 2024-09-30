<?php
// api.php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Ganti dengan ID ekstensi Anda
$extension_id = 'abcdefghijklmnopqrstu1234567890'; // Ganti dengan ID sebenarnya
$origin = "chrome-extension://$extension_id";

header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Credentials: true");

if(isset($_COOKIE['username'])) {
    echo json_encode(["logged_in" => true, "username" => $_COOKIE['username']]);
} else {
    echo json_encode(["logged_in" => false]);
}
?>
