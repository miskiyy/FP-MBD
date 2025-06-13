<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM transaksi WHERE ID_Transaksi = ?");
$stmt->execute([$id]);

$transaksi = $stmt->fetch();

if (!$transaksi) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

if (isset($_POST['update'])) {
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

    $stmt = $pdo->prepare("UPDATE transaksi SET Tanggal_Pemesanan = ?, Total_Awal = ?, REDEEM_CODE = ?, Diskon = ?, Total_Akhir = ?, Status_Pembayaran = ?, Metode_Pembayaran = ?, Tanggal_Dimulai = ?, Tanggal_Berakhir = ?, User_ID = ?, Paket_ID_Paket = ? WHERE ID_Transaksi = ?");
    $stmt->execute([$tanggal_pemesanan, $total_awal, $redeem_code, $diskon, $total_akhir, $status_pembayaran, $metode_pembayaran, $tanggal_dimulai, $tanggal_berakhir, $user_id, $paket_id, $id]);

    header("Location: manage_transaksi.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>Edit Transaksi</h1>

<form action="" method="POST">
    <input name="id_transaksi" class="form-control" value="<?= htmlspecialchars($transaksi['ID_Transaksi']) ?>" readonly><br>
    <input name="tanggal_pemesanan" class="form-control" type="date" value="<?= htmlspecialchars($transaksi['Tanggal_Pemesanan']) ?>"> <br>
    <input name="total_awal" class="form-control" value="<?= htmlspecialchars($transaksi['Total_Awal']) ?>"> <br>
    <input name="redeem_code" class="form-control" value="<?= htmlspecialchars($transaksi['REDEEM_CODE'] ?? '') ?>"> <br>
    <input name="diskon" class="form-control" value="<?= htmlspecialchars($transaksi['Diskon'] ?? '') ?>"> <br>
    <input name="total_akhir" class="form-control" value="<?= htmlspecialchars($transaksi['Total_Akhir']) ?>"> <br>
    <input name="status_pembayaran" class="form-control" value="<?= htmlspecialchars($transaksi['Status_Pembayaran']) ?>"> <br>
    <input name="metode_pembayaran" class="form-control" value="<?= htmlspecialchars($transaksi['Metode_Pembayaran']) ?>"> <br>
    <input name="tanggal_dimulai" class="form-control" type="date" value="<?= htmlspecialchars($transaksi['Tanggal_Dimulai'] ?? '') ?>"> <br>
    <input name="tanggal_berakhir" class="form-control" type="date" value="<?= htmlspecialchars($transaksi['Tanggal_Berakhir'] ?? '') ?>"> <br>
    <input name="user_id" class="form-control" value="<?= htmlspecialchars($transaksi['User_ID']) ?>"> <br>
    <input name="paket_id" class="form-control" value="<?= htmlspecialchars($transaksi['Paket_ID_Paket']) ?>"> <br>

    <input name="update" class="btn btn-primary mt-4" type="submit" value="Simpan">
</form>

</body>
</html>