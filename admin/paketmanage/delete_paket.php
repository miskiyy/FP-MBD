<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("DELETE FROM paket WHERE ID_paket = ?");
$stmt->execute([$id]);

header("Location: manage_paket.php");

exit;

?>