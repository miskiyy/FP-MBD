<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_challenge.php");
    exit();
}

$id = $_GET["id"];
$query = "DELETE FROM challenge WHERE id_challenge = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();

header("Location: manage_challenge.php");
exit();
?>
