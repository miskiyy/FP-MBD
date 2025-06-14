<?php
require_once '../../config/database.php';
require_once '../../models/sertifuser.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"] ?? null;
    $sertif_id = $_POST["sertif_id"] ?? null;

    $sertifUserModel = new SertifUser($pdo);

    if ($user_id && $sertif_id) {
        if ($sertifUserModel->delete($user_id, $sertif_id)) {
            header("Location: manage_sertifuser.php?success=deleted");
            exit();
        } else {
            echo "Gagal menghapus sertifikat user.";
        }
    } else {
        echo "User ID atau Sertifikat ID tidak valid.";
    }
} else {
    echo "Request tidak valid.";
}