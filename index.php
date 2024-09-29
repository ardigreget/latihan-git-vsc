<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = trim($_POST['username']);
    $password_input = $_POST['password'];

    // Mencari pengguna di database
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username_input);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password_input, $hashed_password)) {
            // Menyimpan data pengguna di session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username_input;
            $_SESSION['role'] = $role;

            // Redirect berdasarkan peran
            if ($role === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user_dashboard.php"); // Halaman pengguna biasa
            }
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php 
    if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } 
    ?>
    <form method="POST" action="index.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
