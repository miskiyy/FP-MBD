<?php
require_once('../../config/database.php');
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_paket'];
    $nama = $_POST['nama_paket'];
    $durasi = $_POST['durasi_paket'];
    $harga = $_POST['harga'];

    if ($id && $nama && $durasi && $harga) {
        $stmt = $pdo->prepare("INSERT INTO paket (ID_paket, Nama_paket, Durasi_paket, Harga) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$id, $nama, $durasi, $harga])) {
            $success = "Paket berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan paket.";
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
    <title>Tambah Paket - CodingIn</title>
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
                <h2 class="text-2xl font-bold text-purple-700">Tambah Paket</h2>
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
                    <label class="block text-sm font-medium mb-1">ID Paket</label>
                    <input name="id_paket" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" placeholder="ID Paket" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Paket</label>
                    <input name="nama_paket" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" placeholder="Nama Paket" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Durasi (Bulan)</label>
                    <input name="durasi_paket" type="number" min="1" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" placeholder="Durasi (Bulan)" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Harga</label>
                    <input name="harga" type="number" min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" placeholder="Harga" required>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Tambah Paket</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>