<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_sertifikat.php");
    exit();
}

$id = $_GET["id"];
$stmt = $conn->prepare("DELETE FROM sertifikat WHERE ID_Sertifikat = ?");
$stmt->bind_param("s", $id);
$stmt->execute();

header("Location: manage_sertifikat.php");
exit();
