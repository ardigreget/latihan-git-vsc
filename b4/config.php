<?php
// config.php

$host = 'localhost';
$dbname = 'u934654818_akses';
$username = 'u934654818_akses';
$password = 'Sqn^y^8r'; // Ganti dengan password Anda
$charset = 'utf8mb4';

// Base URL (Ganti jika menggunakan HTTPS)
$base_url = 'http://akses.papindo.id/b4';

// DSN untuk PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Options untuk PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
?>
