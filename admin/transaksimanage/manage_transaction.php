<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM transaksi");
$stmt->execute();
$transaksis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Transaksi - CodingIn</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-7xl ml-0 md:ml-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <h2 class="text-2xl font-bold text-purple-700">📝 Manajemen Transaksi</h2>
                <a href="add_transaksi.php" class="inline-block bg-purple-700 text-white px-5 py-2 rounded-full font-semibold hover:bg-purple-800 transition text-sm shadow">
                    + Tambah Transaksi
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg text-xs sm:text-sm">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">ID Transaksi</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Tanggal Pemesanan</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Total Awal</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Redeem Code</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Diskon</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Total Akhir</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Status Pembayaran</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Metode Pembayaran</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Tanggal Dimulai</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Tanggal Berakhir</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">User</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Paket</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($transaksis as $transaksi): ?>
                            <tr class="hover:bg-purple-50">
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['ID_Transaksi']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['Tanggal_Pemesanan']) ?></td>
                                <td class="py-3 px-4">Rp <?= number_format($transaksi['Total_Awal'], 0, ',', '.') ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['REDEEM_CODE'] ?? '') ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['Diskon'] ?? '') ?></td>
                                <td class="py-3 px-4">Rp <?= number_format($transaksi['Total_Akhir'], 0, ',', '.') ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['Status_Pembayaran']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['Metode_Pembayaran']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['Tanggal_Dimulai'] ?? '') ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['Tanggal_Berakhir'] ?? '') ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['User_ID']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($transaksi['Paket_ID_Paket']) ?></td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-x-2">
                                        <a href='edit_transaksi.php?id=<?= urlencode($transaksi['ID_Transaksi']) ?>'
                                           class='inline-flex items-center justify-center bg-yellow-400 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-yellow-500 transition text-xs shadow-sm'>
                                            Edit
                                        </a>
                                        <a href='delete_transaksi.php?id=<?= urlencode($transaksi['ID_Transaksi']) ?>'
                                           class='inline-flex items-center justify-center bg-red-500 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-red-600 transition text-xs shadow-sm'
                                           onclick='return confirm("Yakin?")'>
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($transaksis)): ?>
                            <tr>
                                <td colspan="13" class="text-center text-gray-400 py-6">Belum ada transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>