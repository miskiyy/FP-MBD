<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM paket WHERE ID_paket = ?");
$stmt->execute([$id]);

$paket = $stmt->fetch();

if (!$paket) {
    echo "<script>alert('Paket tidak ditemukan');</script>";
    header("Location: manage_paket.php");

    exit;
}

if (isset($_POST['update'])) {
    $nama = $_POST['nama_paket'];
    $durasi = $_POST['durasi_paket'];
    $harga = $_POST['harga'];

    $stmt = $pdo->prepare("UPDATE paket SET Nama_paket = ?, Durasi_paket = ?, Harga = ? WHERE ID_paket = ?");
    $stmt->execute([$nama, $durasi, $harga, $id]);

    header("Location: manage_paket.php");

    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Paket</title>
</head>
<body>

<h1>Edit Paket</h1>

<form action="" method="POST">
    <input name="nama_paket" class="form-control" value="<?= htmlspecialchars($paket['Nama_paket']); ?>"> <br>
    <input name="durasi_paket" class="form-control" value="<?= $paket['Durasi_paket']; ?>"> <br>
    <input name="harga" class="form-control" value="<?= $paket['Harga']; ?>"> <br>
    <input name="update" class="btn btn-primary mt-4" type="submit" value="Simpan">
</form>

</body>
</html>
