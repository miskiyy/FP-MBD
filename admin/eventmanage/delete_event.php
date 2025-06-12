<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_event.php");
    exit();
}

$id = $_GET["id"];
$query = "DELETE FROM event WHERE ID_event = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    header("Location: manage_event.php?success=1");
} else {
    header("Location: manage_event.php?error=1");
}
exit();