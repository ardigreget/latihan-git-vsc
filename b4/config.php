<?php
// config.php

$host = 'localhost';
$dbname = 'u934654818_akses';
$username = 'u934654818_akses';
$password = 'Sqn^y^8r';
$charset = 'utf8mb4';

// Base URL
$base_url = 'http://akses.papindo.id/b4'; // Ganti dengan HTTPS jika memungkinkan

// DSN untuk PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Options untuk PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
?>
