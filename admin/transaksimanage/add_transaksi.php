<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

if (isset($_POST['add'])) {
    $id = $_POST['id_transaksi'];
    $tanggal_pemesanan = $_POST['tanggal_pemesanan'];
    $total_awal = $_POST['total_awal'];
    $redeem_code = $_POST['redeem_code'];
    $diskon = $_POST['diskon'];
    $total_akhir = $_POST['total_akhir'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $tanggal_dimulai = $_POST['tanggal_dimulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $user_id = $_POST['user_id'];
    $paket_id = $_POST['paket_id'];

    $stmt = $pdo->prepare("INSERT INTO transaksi (ID_Transaksi, Tanggal_Pemesanan, Total_Awal, REDEEM_CODE, Diskon, Total_Akhir, Status_Pembayaran, Metode_Pembayaran, Tanggal_Dimulai, Tanggal_Berakhir, User_ID, Paket_ID_Paket) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id, $tanggal_pemesanan, $total_awal, $redeem_code, $diskon, $total_akhir, $status_pembayaran, $metode_pembayaran, $tanggal_dimulai, $tanggal_berakhir, $user_id, $paket_id]);

    header("Location: manage_transaksi.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>Tambah Transaksi</h1>

<form action="" method="POST">
    <input name="id_transaksi" class="form-control" placeholder="ID Transaksi"><br>
    <input name="tanggal_pemesanan" class="form-control" type="date"><br>
    <input name="total_awal" class="form-control" placeholder="Total Awal"><br>
    <input name="redeem_code" class="form-control" placeholder="Redeem Code"><br>
    <input name="diskon" class="form-control" placeholder="Diskon"><br>
    <input name="total_akhir" class="form-control" placeholder="Total Akhir"><br>
    <input name="status_pembayaran" class="form-control" placeholder="Status Pembayaran"><br>
    <input name="metode_pembayaran" class="form-control" placeholder="Metode Pembayaran"><br>
    <input name="tanggal_dimulai" class="form-control" type="date"><br>
    <input name="tanggal_berakhir" class="form-control" type="date"><br>
    <input name="user_id" class="form-control" placeholder="User ID"><br>
    <input name="paket_id" class="form-control" placeholder="Paket ID"><br>
    <input name="add" class="btn btn-primary mt-4" type="submit" value="Tambah">
</form>

</body>
</html>