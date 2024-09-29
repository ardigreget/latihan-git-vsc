<?php
// Konfigurasi Database
$host = "localhost";      // Host database
$db_user = "sql_akses_wallmv";        // Username database
$db_password = "5531dc927f2ce";        // Password database
$db_name = "sql_akses_wallmv"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
