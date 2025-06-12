<?php
require_once '../config/database.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Cek di tabel user
    $stmtUser = $pdo->prepare("SELECT * FROM user WHERE Email = ? AND password = ?");
    $stmtUser->execute([$email, $password]);
    $user = $stmtUser->fetch();

    // Cek di tabel karyawan
    $stmtKaryawan = $pdo->prepare("SELECT * FROM karyawan WHERE Email = ? AND password = ?");
    $stmtKaryawan->execute([$email, $password]);
    $karyawan = $stmtKaryawan->fetch();

    if ($user) {
        $_SESSION['role'] = 'user';
        $_SESSION['name'] = $user['First_Name'];
        $_SESSION['ID'] = $user['ID']; // optional: kalau butuh ID user
        header("Location: dashboard_user.php");
        exit();
    } elseif ($karyawan) {
        $_SESSION['role'] = 'karyawan';
        $_SESSION['name'] = $karyawan['First_Name'];
        $_SESSION['NIK'] = $karyawan['NIK']; // INI WAJIB BUAT DASHBOARD ADMIN
        header("Location: dashboard_admin.php");
        exit();
    } else {
        $error = "Email atau password salah.";
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

    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</body>
</html>
