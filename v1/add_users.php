<?php
// add_users.php

require 'config.php'; // Pastikan file ini ada di direktori yang sama

$users = [
    [
        'username' => 'admin',
        'password' => 'admin123', // Kata sandi untuk admin
        'roles' => 'admin'
    ],
    [
        'username' => 'user1',
        'password' => 'user123', // Kata sandi untuk user1
        'roles' => 'user'
    ],
    [
        'username' => 'user2',
        'password' => 'user123', // Kata sandi untuk user2
        'roles' => 'user'
    ],
];

// Loop melalui setiap pengguna dan tambahkan ke database
foreach ($users as $user) {
    // Cek apakah pengguna sudah ada
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$user['username']]);
    if ($stmt->fetch()) {
        echo "Pengguna {$user['username']} sudah ada.\n";
        continue;
    }

    // Hash kata sandi
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

    // Masukkan pengguna ke database
    $stmt = $pdo->prepare('INSERT INTO users (username, password, roles) VALUES (?, ?, ?)');
    $stmt->execute([$user['username'], $hashed_password, $user['roles']]);

    echo "Pengguna {$user['username']} berhasil ditambahkan.\n";
}
?>
