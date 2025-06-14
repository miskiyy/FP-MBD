<?php
require_once '../../config/database.php';
require_once '../../models/user.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_GET['id'] ?? '';
$userModel = new User($pdo);

$user = $userModel->getUserById($id);
if (!$user) {
    header("Location: manage_user.php?error=notfound");
    exit();
}

// Hapus user
if ($userModel->deleteUser($id)) {
    header("Location: manage_user.php?success=deleted");
    exit();
} else {
    header("Location: manage_user.php?error=deletefail");
    exit();
}
?>