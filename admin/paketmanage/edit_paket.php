<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM paket WHERE ID_paket = ?");
$stmt->execute([$id]);
$paket = $stmt->fetch();

if (!$paket) {
    echo "<script>alert('Paket tidak ditemukan');</script>";
    header("Location: manage_paket.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $nama = $_POST['nama_paket'];
    $durasi = $_POST['durasi_paket'];
    $harga = $_POST['harga'];

    if ($nama && $durasi && $harga) {
        $stmt = $pdo->prepare("UPDATE paket SET Nama_paket = ?, Durasi_paket = ?, Harga = ? WHERE ID_paket = ?");
        if ($stmt->execute([$nama, $durasi, $harga, $id])) {
            $success = "Paket berhasil diperbarui!";
            // Refresh data
            $paket['Nama_paket'] = $nama;
            $paket['Durasi_paket'] = $durasi;
            $paket['Harga'] = $harga;
        } else {
            $error = "Gagal memperbarui paket.";
        }
    } else {
        $error = "Semua field wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Paket - CodingIn</title>
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
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-md ml-0 md:ml-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-purple-700">Edit Paket</h2>
                <a href="manage_paket.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Paket</label>
                    <input name="nama_paket" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($paket['Nama_paket']); ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Durasi (Bulan)</label>
                    <input name="durasi_paket" type="number" min="1" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= $paket['Durasi_paket']; ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Harga</label>
                    <input name="harga" type="number" min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= $paket['Harga']; ?>" required>
                </div>
                <div class="flex justify-end mt-6">
                    <button name="update" type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Simpan</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>