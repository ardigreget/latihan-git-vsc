<?php
include '../db.php';
include '../functions.php';
check_login();

if (!is_admin()) {
    header("Location: ../users/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $role, $status]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
</head>
<body>
    <h2>Create User</h2>
    <form method="POST">
        <label>Username:</label><input type="text" name="username" required>
        <label>Password:</label><input type="password" name="password" required>
        <label>Role:</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <label>Status:</label>
        <select name="status">
            <option value="active">Active</option>
            <option value="suspend">Suspend</option>
            <option value="pending">Pending</option>
        </select>
        <button type="submit">Create</button>
    </form>
</body>
</html>
