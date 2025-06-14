<?php
require_once '../../config/database.php';
require_once '../../models/sertifikat.php';
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
$sertifikatModel = new Sertifikat($pdo);
$sertifikatModel->delete($id);

header("Location: manage_sertifikat.php");
exit();
