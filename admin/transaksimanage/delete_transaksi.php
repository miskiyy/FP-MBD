<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM transaksi WHERE ID_Transaksi = ?");
$stmt->execute([$id]);

header("Location: manage_transaksi.php");

exit();
?>