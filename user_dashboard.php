<?php
session_start();
require_once 'config.php';

// Cek apakah pengguna login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h2>User Dashboard</h2>
    <p>Selamat datang, <?php echo htmlspecialchars($username); ?>! <a href="logout.php">Logout</a></p>
    
    <?php if($role === 'admin'): ?>
        <p><a href="admin.php">Go to Admin Dashboard</a></p>
    <?php endif; ?>

    <p>Konten pengguna biasa dapat ditambahkan di sini.</p>
</body>
</html>
