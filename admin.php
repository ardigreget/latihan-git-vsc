<?php
session_start();
require_once 'config.php';

// Cek apakah pengguna login dan merupakan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle penambahan pengguna
if (isset($_POST['add_user'])) {
    $new_username = trim($_POST['username']);
    $new_password = $_POST['password'];
    $new_role = $_POST['role']; // 'admin' atau 'user'

    // Validasi input
    if (empty($new_username) || empty($new_password) || empty($new_role)) {
        $add_error = "Semua field harus diisi.";
    } else {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $new_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $add_error = "Username sudah digunakan.";
        } else {
            // Hash password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Menambahkan pengguna ke database
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $new_username, $hashed_password, $new_role);

            if ($stmt->execute()) {
                $add_success = "Pengguna berhasil ditambahkan.";
            } else {
                $add_error = "Gagal menambahkan pengguna: " . $stmt->error;
            }
        }
    }
}

// Handle penghapusan pengguna
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    // Jangan izinkan admin untuk menghapus dirinya sendiri
    if ($delete_id === $_SESSION['user_id']) {
        $delete_error = "Anda tidak dapat menghapus diri sendiri.";
    } else {
        // Hapus pengguna
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $delete_success = "Pengguna berhasil dihapus.";
        } else {
            $delete_error = "Gagal menghapus pengguna: " . $stmt->error;
        }
    }
}

// Ambil semua pengguna
$stmt = $conn->prepare("SELECT id, username, role FROM users");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">Logout</a></p>

    <h3>Tambah Pengguna Baru</h3>
    <?php 
    if(isset($add_error)) { echo "<p style='color:red;'>$add_error</p>"; } 
    if(isset($add_success)) { echo "<p style='color:green;'>$add_success</p>"; }
    ?>
    <form method="POST" action="admin.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <label>Role:</label><br>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>
        
        <button type="submit" name="add_user">Tambah Pengguna</button>
    </form>

    <h3>Daftar Pengguna</h3>
    <?php 
    if(isset($delete_error)) { echo "<p style='color:red;'>$delete_error</p>"; } 
    if(isset($delete_success)) { echo "<p style='color:green;'>$delete_success</p>"; }
    ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo ucfirst($user['role']); ?></td>
                    <td>
                        <!-- Link untuk menghapus pengguna -->
                        <a href="admin.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
