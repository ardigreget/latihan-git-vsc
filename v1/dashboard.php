<?php
// dashboard.php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$roles = $_SESSION['roles'];
$message = '';

// Handle CRUD operations
if ($roles === 'admin') {
    // CREATE
    if (isset($_POST['create'])) {
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];
        $new_roles = $_POST['new_roles'];

        // Cek apakah username sudah ada
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$new_username]);
        if ($stmt->fetch()) {
            $message = 'Username sudah ada!';
        } else {
            // Insert pengguna baru
            $stmt = $pdo->prepare('INSERT INTO users (username, password, roles) VALUES (?, ?, ?)');
            $stmt->execute([$new_username, $new_password, $new_roles]);
            $message = 'Pengguna berhasil ditambahkan!';
        }
    }

    // UPDATE
    if (isset($_POST['update'])) {
        $update_id = $_POST['update_id'];
        $update_username = $_POST['update_username'];
        $update_password = $_POST['update_password'];
        $update_roles = $_POST['update_roles'];

        $stmt = $pdo->prepare('UPDATE users SET username = ?, password = ?, roles = ? WHERE id = ?');
        $stmt->execute([$update_username, $update_password, $update_roles, $update_id]);
        $message = 'Pengguna berhasil diperbarui!';
    }

    // DELETE
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        // Jangan biarkan admin menghapus dirinya sendiri
        if ($delete_id == $_SESSION['user_id']) {
            $message = 'Anda tidak dapat menghapus diri sendiri!';
        } else {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$delete_id]);
            $message = 'Pengguna berhasil dihapus!';
        }
    }
}

// Fetch semua pengguna untuk admin
if ($roles === 'admin') {
    $stmt = $pdo->query('SELECT * FROM users');
    $users = $stmt->fetchAll();
}

// Fetch data pengguna yang sedang login
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$current_user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Dashboard</h2>
    <p>Selamat datang, <?php echo htmlspecialchars($current_user['username']); ?>!</p>
    <p><a href="logout.php">Logout</a></p>

    <h3>Data Anda:</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Roles</th>
            <th>Tanggal Register</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($current_user['username']); ?></td>
            <td><?php echo htmlspecialchars($current_user['password']); ?></td>
            <td><?php echo htmlspecialchars($current_user['roles']); ?></td>
            <td><?php echo htmlspecialchars($current_user['tanggal_register']); ?></td>
        </tr>
    </table>

    <?php if ($roles === 'admin'): ?>
        <h3>Kelola Pengguna</h3>
        <?php if ($message): ?>
            <p style="color:green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Form Tambah Pengguna -->
        <h4>Tambah Pengguna</h4>
        <form method="post" action="dashboard.php">
            <input type="hidden" name="create" value="1">
            <label>Username:</label><br>
            <input type="text" name="new_username" required><br><br>
            
            <label>Password:</label><br>
            <input type="password" name="new_password" required><br><br>
            
            <label>Roles:</label><br>
            <select name="new_roles">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br><br>
            
            <button type="submit">Tambah</button>
        </form>

        <!-- Daftar Pengguna -->
        <h4>Daftar Pengguna</h4>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Roles</th>
                <th>Tanggal Register</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <form method="post" action="dashboard.php">
                        <td><?php echo $user['id']; ?></td>
                        <td>
                            <input type="text" name="update_username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </td>
                        <td>
                            <input type="text" name="update_password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
                        </td>
                        <td>
                            <select name="update_roles">
                                <option value="user" <?php if($user['roles'] === 'user') echo 'selected'; ?>>User</option>
                                <option value="admin" <?php if($user['roles'] === 'admin') echo 'selected'; ?>>Admin</option>
                            </select>
                        </td>
                        <td><?php echo htmlspecialchars($user['tanggal_register']); ?></td>
                        <td>
                            <input type="hidden" name="update_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="update">Update</button>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="dashboard.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
