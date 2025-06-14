<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/challenge.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_challenge.php");
    exit();
}

$id = $_GET["id"];
$challengeModel = new Challenge($pdo);

$challengeModel->deleteChallenge($id);

header("Location: manage_challenge.php");
exit();