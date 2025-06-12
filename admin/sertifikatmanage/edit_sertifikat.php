<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_sertifikat.php");
    exit();
}

$id = $_GET["id"];
$stmt = $conn->prepare("SELECT * FROM sertifikat WHERE ID_Sertifikat = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$sertif = $result->fetch_assoc();

if (!$sertif) {
    echo "Sertifikat tidak ditemukan.";
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["Nama_Sertifikat"];
    $templatePath = $sertif["sertif_template"]; // default: template lama

    // Jika ada file baru diupload
    if (isset($_FILES["template_file"]) && $_FILES["template_file"]["error"] == UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/sertif/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = basename($_FILES["template_file"]["name"]);
        $targetPath = $uploadDir . uniqid("template_") . "_" . $filename;

        if (move_uploaded_file($_FILES["template_file"]["tmp_name"], $targetPath)) {
            $templatePath = $targetPath;
        } else {
            $error = "Gagal upload file!";
        }
    }

    if (!$error) {
        $stmtUpdate = $conn->prepare("UPDATE sertifikat SET Nama_Sertifikat=?, sertif_template=? WHERE ID_Sertifikat=?");
        $stmtUpdate->bind_param("sss", $nama, $templatePath, $id);

        if ($stmtUpdate->execute()) {
            $success = "Sertifikat berhasil diperbarui!";
            header("Location: manage_sertifikat.php");
            exit();
        } else {
            $error = "Gagal update: " . $stmtUpdate->error;
        }
    }
}
?>

<h2>Edit Sertifikat</h2>
<form method="post" enctype="multipart/form-data">
    <label>Nama Sertifikat:</label><br>
    <input type="text" name="Nama_Sertifikat" value="<?= htmlspecialchars($sertif['Nama_Sertifikat']) ?>" required><br><br>

    <label>Upload Template Baru (Opsional):</label><br>
    <input type="file" name="template_file" accept=".pdf,.jpg,.png"><br>
    <small>File saat ini: <?= htmlspecialchars(basename($sertif['sertif_template'])) ?></small><br><br>

    <button type="submit">Simpan Perubahan</button>
</form>

<br>
<a href="manage_sertifikat.php">Kembali</a>
<p style="color: green;"><?= htmlspecialchars($success) ?></p>
<p style="color: red;"><?= htmlspecialchars($error) ?></p>