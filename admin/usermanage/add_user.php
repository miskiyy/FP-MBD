<?php
require_once '../../config/database.php';
require_once '../helpers/id_generator.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = generateIDUnique($conn, 'user', 'ID', 'US', 4); // Contoh: USX1Y2
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $jk = $_POST['jk'];
    $pekerjaan = $_POST['pekerjaan'];
    $kota = $_POST['kota'];
    $negara = $_POST['negara'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $tentang = $_POST['tentang'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $password = $_POST['password'];

    $query = "INSERT INTO user (ID, First_Name, Last_Name, Jenis_kelamin, Pekerjaan, Kota, Negara, Nomor_Telepon, Email, Tentang_Saya, Tanggal_Lahir, password)
              VALUES ('$id', '$fname', '$lname', '$jk', '$pekerjaan', '$kota', '$negara', '$telepon', '$email', '$tentang', '$tanggal_lahir', '$password')";

    if (mysqli_query($conn, $query)) {
        header("Location: manage_user.php");
        exit();
    } else {
        echo "Gagal menambah user: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include __DIR__ . '/assets/template/admin.php'; ?>
    <main class="flex-1 p-8">
        <h2>Tambah User Baru</h2>
        <form method="post">
            Nama Depan: <input type="text" name="fname" required><br>
            Nama Belakang: <input type="text" name="lname"><br>
            Jenis Kelamin: <input type="text" name="jk" required><br>
            Pekerjaan: <input type="text" name="pekerjaan" required><br>
            Kota: <input type="text" name="kota" required><br>
            Negara: <input type="text" name="negara" required><br>
            Telepon: <input type="text" name="telepon" required><br>
            Email: <input type="email" name="email" required><br>
            Tentang Saya: <input type="text" name="tentang"><br>
            Tanggal Lahir: <input type="date" name="tanggal_lahir" required><br>
            Password: <input type="text" name="password" required><br>
            <button type="submit">Simpan</button>
    </main>
</body>
</form>
