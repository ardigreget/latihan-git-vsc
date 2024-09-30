<?php
// api.php
session_start();
require 'config.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: chrome-extension://*"); // Ganti dengan ID ekstensi Anda
header("Access-Control-Allow-Credentials: true");

if(isset($_COOKIE['username'])) {
    echo json_encode(["logged_in" => true, "username" => $_COOKIE['username']]);
} else {
    echo json_encode(["logged_in" => false]);
}
?>
