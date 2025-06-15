<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_transaction.php");
    exit();
}

$id_transaksi = $_GET['id'];

// Update status transaksi jadi "Dibatalkan" dan hapus bukti pembayaran
$stmt = $pdo->prepare("UPDATE transaksi SET Status_Pembayaran = 'Dibatalkan', Bukti_Pembayaran = NULL WHERE ID_Transaksi = ?");
$stmt->execute([$id_transaksi]);

header("Location: manage_transaction.php?cancel=1");
exit();