<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_course.php");
    exit();
}

$id = $_GET["id"];
$query = "DELETE FROM courses WHERE ID_courses = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    header("Location: manage_course.php");
    exit();
} else {
    echo "Gagal menghapus course: " . $stmt->error;
}
?>
