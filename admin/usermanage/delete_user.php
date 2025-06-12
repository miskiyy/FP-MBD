<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_GET['id'];
$query = "DELETE FROM user WHERE ID='$id'";

if (mysqli_query($conn, $query)) {
    header("Location: manage_user.php");
    exit();
} else {
    echo "Gagal hapus: " . mysqli_error($conn);
}
?>