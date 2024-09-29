<?php
require_once 'config.php';

// Tentukan username dan password admin
$admin_username = "admin";
$admin_password = "admin123"; // Ganti dengan password yang kuat

// Cek apakah admin sudah ada
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Admin sudah ada.";
} else {
    // Hash password
    $hashed_password = password_hash($admin_password, PASSWORD_BCRYPT);
    
    // Menambahkan admin ke database
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->bind_param("ss", $admin_username, $hashed_password);
    
    if ($stmt->execute()) {
        echo "Admin berhasil dibuat.";
    } else {
        echo "Gagal membuat admin: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
