<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/challenge.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$challengeModel = new Challenge($pdo);
$challenges = $challengeModel->getAllChallenges();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Challenge</title>
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
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-6xl ml-0 md:ml-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <h2 class="text-2xl font-bold text-purple-700">Daftar Challenge</h2>
                <a href="add_challenge.php" class="inline-block bg-purple-700 text-white px-5 py-2 rounded-full font-semibold hover:bg-purple-800 transition text-sm shadow">
                    + Tambah Challenge
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg text-xs sm:text-sm">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">ID</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">Nama</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">Deskripsi</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b w-32">Tgl Mulai</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b w-32">Tgl Berakhir</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">Kuota</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">Hadiah</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($challenges as $row): ?>
                            <tr class="hover:bg-purple-50">
                                <td class="py-3 px-4"><?= htmlspecialchars($row['id_challenge']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($row['nama_challenge']) ?></td>
                                <td class="py-3 px-4 max-w-xs truncate text-gray-700" title="<?= htmlspecialchars($row['deskripsi']) ?>">
                                    <?= htmlspecialchars($row['deskripsi']) ?>
                                </td>
                                <td class="py-3 px-4 w-34"><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
                                <td class="py-3 px-4 w-34"><?= htmlspecialchars($row['tanggal_berakhir']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($row['kuota_pemenang']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($row['hadiah']) ?></td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center gap-x-2">
                                        <a href="edit_challenge.php?id=<?= urlencode($row['id_challenge']) ?>"
                                           class="inline-flex items-center justify-center bg-yellow-400 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-yellow-500 transition text-xs shadow-sm">
                                            Edit
                                        </a>
                                        <a href="delete_challenge.php?id=<?= urlencode($row['id_challenge']) ?>"
                                           class="inline-flex items-center justify-center bg-red-500 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-red-600 transition text-xs shadow-sm"
                                           onclick="return confirm('Yakin hapus?')">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($challenges)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-gray-400 py-6">Belum ada challenge yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
