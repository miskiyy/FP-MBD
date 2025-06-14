<?php
require_once '../../config/database.php';
require_once '../../models/sertifikat.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_sertifikat.php");
    exit();
}

$sertifikatModel = new Sertifikat($pdo);
$id = $_GET["id"];
$sertif = $sertifikatModel->getById($id);

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

    if ($sertifikatModel->update($id, $nama, $templatePath)) {
        $success = "Sertifikat berhasil diperbarui!";
        $sertif = $sertifikatModel->getById($id);
    } else {
        $error = "Gagal update.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Sertifikat - CodingIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-lg ml-0 md:ml-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-purple-700">Edit Sertifikat</h2>
                <a href="manage_sertifikat.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Sertifikat</label>
                    <input type="text" name="Nama_Sertifikat" value="<?= htmlspecialchars($sertif['Nama_Sertifikat']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Upload Template Baru (Opsional)</label>
                    <input type="file" name="template_file" accept=".pdf,.jpg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    <small class="text-gray-500">File saat ini: <?= htmlspecialchars(basename($sertif['sertif_template'])) ?></small>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>