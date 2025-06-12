<?php
require_once '../config/database.php';
session_start();

$success = "";
$error = "";

// Kalau form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = uniqid(); // auto generate 6-char ID (bisa disesuaikan)
    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $gender = $_POST["jenis_kelamin"];
    $pekerjaan = $_POST["pekerjaan"];
    $kota = $_POST["kota"];
    $negara = $_POST["negara"];
    $telepon = $_POST["nomor_telepon"];
    $email = $_POST["email"];
    $tentang = $_POST["tentang_saya"];
    $lahir = $_POST["tanggal_lahir"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    // Validasi password
    if ($password !== $confirm) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT * FROM user WHERE Email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email sudah digunakan.";
        } else {
            // Simpan user baru
            $stmt = $pdo->prepare("INSERT INTO user 
                (ID, First_Name, Last_Name, Jenis_kelamin, Pekerjaan, Kota, Negara, Nomor_Telepon, Email, Tentang_Saya, Tanggal_Lahir, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $result = $stmt->execute([
                $id, $first, $last, $gender, $pekerjaan, $kota, $negara, $telepon, $email, $tentang, $lahir, $password
            ]);

            if ($result) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Gagal menyimpan data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
</head>
<body>
    <h2>Form Registrasi</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>First Name:</label><br>
        <input type="text" name="first_name" required><br><br>

        <label>Last Name:</label><br>
        <input type="text" name="last_name"><br><br>

        <label>Jenis Kelamin (L/P):</label><br>
        <input type="text" name="jenis_kelamin" maxlength="1" required><br><br>

        <label>Pekerjaan:</label><br>
        <input type="text" name="pekerjaan" required><br><br>

        <label>Kota:</label><br>
        <input type="text" name="kota" required><br><br>

        <label>Negara:</label><br>
        <input type="text" name="negara" required><br><br>

        <label>Nomor Telepon:</label><br>
        <input type="text" name="nomor_telepon" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Tentang Saya:</label><br>
        <textarea name="tentang_saya"></textarea><br><br>

        <label>Tanggal Lahir:</label><br>
        <input type="date" name="tanggal_lahir" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="confirm" required><br><br>

        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>