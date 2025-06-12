<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

function generateRandomID($prefix, $length = 4) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $prefix . $randomString;
}

$error = "";
$success = "";
$templatePath = null;
$newId = generateRandomID("SRF");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID_Sertifikat'] ?? $newId;
    $nama = $_POST['Nama_Sertifikat'] ?? '';

    // Handle file upload
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
        $stmt = $conn->prepare("INSERT INTO sertifikat (ID_Sertifikat, Nama_Sertifikat, sertif_template) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $id, $nama, $templatePath);

        if ($stmt->execute()) {
            $success = "Sertifikat berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah: " . $stmt->error;
        }
    }
}
?>

<h2>Tambah Sertifikat</h2>
<form method="post" enctype="multipart/form-data">
    <!-- <label>ID Sertifikat (auto-generated):</label><br>
    <input type="text" name="ID_Sertifikat" value="<?= htmlspecialchars($newId) ?>" readonly><br><br> -->

    <label>Nama Sertifikat:</label><br>
    <input type="text" name="Nama_Sertifikat" required><br><br>

    <label>Upload Template Sertifikat (PDF/JPG/PNG):</label><br>
    <input type="file" name="template_file" accept=".pdf,.jpg,.png" required><br><br>

    <button type="submit">Tambah</button>
</form>

<br>
<a href="manage_sertifikat.php">Kembali</a>
<p style="color: green;"><?= htmlspecialchars($success) ?></p>
<p style="color: red;"><?= htmlspecialchars($error) ?></p>
