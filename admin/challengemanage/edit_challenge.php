<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/challenge.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_challenge.php");
    exit();
}

$id = $_GET["id"];
$challengeModel = new Challenge($pdo);
$challenge = $challengeModel->getChallengeById($id);

if (!$challenge) {
    die("Challenge tidak ditemukan.");
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama_challenge"];
    $deskripsi = $_POST["deskripsi"];
    $mulai = $_POST["tanggal_mulai"];
    $akhir = $_POST["tanggal_berakhir"];
    $kuota = $_POST["kuota_pemenang"];
    $hadiah = $_POST["hadiah"];

    $dataUpdate = [$nama, $deskripsi, $mulai, $akhir, $kuota, $hadiah, $id];

    if ($challengeModel->updateChallenge($dataUpdate)) {
        $success = "Berhasil diperbarui!";
        // Refresh data
        $challenge = $challengeModel->getChallengeById($id);
    } else {
        $error = "Gagal update challenge.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Challenge - CodingIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-xl ml-0 md:ml-12">
            <h2 class="text-2xl font-bold text-purple-700 mb-4">Edit Challenge</h2>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Challenge</label>
                    <input type="text" name="nama_challenge" value="<?= htmlspecialchars($challenge['nama_challenge']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required><?= htmlspecialchars($challenge['deskripsi']) ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="<?= htmlspecialchars($challenge['tanggal_mulai']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Berakhir</label>
                        <input type="date" name="tanggal_berakhir" value="<?= htmlspecialchars($challenge['tanggal_berakhir']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Kuota Pemenang</label>
                        <input type="number" name="kuota_pemenang" value="<?= htmlspecialchars($challenge['kuota_pemenang']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Hadiah</label>
                        <input type="text" name="hadiah" value="<?= htmlspecialchars($challenge['hadiah']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <a href="manage_challenge.php" class="text-purple-700 hover:underline">← Kembali</a>
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Simpan</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>