<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/course.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_course.php");
    exit();
}

$id = $_GET["id"];
$courseModel = new Course($pdo);

if ($courseModel->delete($id)) {
    header("Location: manage_course.php");
    exit();
} else {
    echo "Gagal menghapus course.";
}
?>