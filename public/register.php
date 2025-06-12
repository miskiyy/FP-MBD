<?php
require_once '../config/database.php';
require_once '../admin/helpers/id_generator.php';
session_start();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = function_exists('generateIDUnique') ? generateIDUnique($conn, 'user', 'ID', 'US', 4) : uniqid('US');
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

    if ($password !== $confirm) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        // Cek email sudah terdaftar
        $stmt = $conn->prepare("SELECT ID FROM user WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email sudah digunakan.";
        } else {
            // Simpan user baru
            $stmt = $conn->prepare("INSERT INTO user 
                (ID, First_Name, Last_Name, Jenis_kelamin, Pekerjaan, Kota, Negara, Nomor_Telepon, Email, Tentang_Saya, Tanggal_Lahir, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $id, $first, $last, $gender, $pekerjaan, $kota, $negara, $telepon, $email, $tentang, $lahir, $password);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
            } else {
                $error = "Gagal menyimpan data.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <link rel="stylesheet" type="text/css" href="assets/css/auth.css">
</head>
<body>
<section>
    <h2>Form Registrasi</h2>
    <?php if ($success): ?>
        <p class="message success"><?= $success ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="message error"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" novalidate>
        <label for="first_name">First Name:</label>
        <input id="first_name" type="text" name="first_name" required autocomplete="given-name" />

        <label for="last_name" class="mt-4">Last Name:</label>
        <input id="last_name" type="text" name="last_name" autocomplete="family-name" />

        <label for="jenis_kelamin" class="mt-4">Jenis Kelamin (L/P):</label>
        <input id="jenis_kelamin" type="text" name="jenis_kelamin" maxlength="1" required />

        <label for="pekerjaan" class="mt-4">Pekerjaan:</label>
        <input id="pekerjaan" type="text" name="pekerjaan" required />

        <label for="kota" class="mt-4">Kota:</label>
        <input id="kota" type="text" name="kota" required />

        <label for="negara" class="mt-4">Negara:</label>
        <input id="negara" type="text" name="negara" required />

        <label for="nomor_telepon" class="mt-4">Nomor Telepon:</label>
        <input id="nomor_telepon" type="text" name="nomor_telepon" required />

        <label for="email" class="mt-4">Email:</label>
        <input id="email" type="email" name="email" required autocomplete="email" />

        <label for="tentang_saya" class="mt-4">Tentang Saya:</label>
        <textarea id="tentang_saya" name="tentang_saya" rows="3"></textarea>

        <label for="tanggal_lahir" class="mt-4">Tanggal Lahir:</label>
        <input id="tanggal_lahir" type="date" name="tanggal_lahir" required />

        <label for="password" class="mt-4">Password:</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" />

        <label for="confirm" class="mt-4">Konfirmasi Password:</label>
        <input id="confirm" type="password" name="confirm" required autocomplete="new-password" />

        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</section>
</body>
</html>