<?php
require_once '../config/database.php';
session_start();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = uniqid();
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
        $stmt = $pdo->prepare("SELECT * FROM user WHERE Email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email sudah digunakan.";
        } else {
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center">Form Registrasi</h3>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin (L/P)</label>
                    <input type="text" name="jenis_kelamin" class="form-control" maxlength="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kota</label>
                    <input type="text" name="kota" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Negara</label>
                    <input type="text" name="negara" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tentang Saya</label>
                    <textarea name="tentang_saya" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>

            <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</div>
</body>
</html>
