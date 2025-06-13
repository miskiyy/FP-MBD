<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

if (isset($_POST['add'])) {
    $id = $_POST['id_paket'];
    $nama = $_POST['nama_paket'];
    $durasi = $_POST['durasi_paket'];
    $harga = $_POST['harga'];

    $stmt = $pdo->prepare("INSERT INTO paket (ID_paket, Nama_paket, Durasi_paket, Harga) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $nama, $durasi, $harga]);

    header("Location: manage_paket.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Paket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>Tambah Paket</h1>

<form action="" method="POST">
    <input name="id_paket" class="form-control" placeholder="ID Paket"><br>
    <input name="nama_paket" class="form-control" placeholder="Nama Paket"><br>
    <input name="durasi_paket" class="form-control" placeholder="Durasi (Bulan)"><br>
    <input name="harga" class="form-control" placeholder="Harga"><br>
    <input name="add" class="btn btn-primary mt-4" type="submit" value="Tambah">
</form>

</body>
</html>
