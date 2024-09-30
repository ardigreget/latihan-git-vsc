<?php
// insert_user.php
require 'config.php';

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Data pengguna
    $user = "user1";
    $pass = password_hash("password123", PASSWORD_DEFAULT);
    
    // Insert data dengan prepared statement
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $user, 'password' => $pass]);
    
    echo "New user created successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
