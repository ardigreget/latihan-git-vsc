<?php
// Konfigurasi Database
$host = "localhost";      // Host database
$db_user = "u934654818_akses";        // Username database
$db_password = "Sqn^y^8r";        // Password database
$db_name = "u934654818_akses"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
