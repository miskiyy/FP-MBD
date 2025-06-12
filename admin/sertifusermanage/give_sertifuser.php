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
        // Cegah duplikat
        $stmt = $conn->prepare("SELECT * FROM sertifuser WHERE User_ID = ? AND Sertifikat_ID_Sertifikat = ?");
        $stmt->bind_param("ss", $user_id, $sertif_id);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows == 0) {
            $insert = $conn->prepare("INSERT INTO sertifuser (User_ID, Sertifikat_ID_Sertifikat) VALUES (?, ?)");
            $insert->bind_param("ss", $user_id, $sertif_id);

            if ($insert->execute()) {
                header("Location: manage_sertifuser.php?success=1");
                exit();
            } else {
                echo "Gagal insert: " . $insert->error;
            }
        } else {
            header("Location: manage_sertifuser.php?message=exists");
            exit();
        }
    } else {
        echo "User ID atau Sertifikat ID kosong.";
    }
} else {
    echo "Invalid request method.";
}
