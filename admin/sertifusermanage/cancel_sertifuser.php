<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"] ?? null;
    $sertif_id = $_POST["sertif_id"] ?? null;

    if ($user_id && $sertif_id) {
        $stmt = $conn->prepare("DELETE FROM sertifuser WHERE User_ID = ? AND Sertifikat_ID_Sertifikat = ?");
        $stmt->bind_param("ss", $user_id, $sertif_id);

        if ($stmt->execute()) {
            header("Location: manage_sertifuser.php?success=deleted");
            exit();
        } else {
            echo "Gagal menghapus: " . $stmt->error;
        }
    } else {
        echo "User ID atau Sertifikat ID tidak valid.";
    }
} else {
    echo "Request tidak valid.";
}
