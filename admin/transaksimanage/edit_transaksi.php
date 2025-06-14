<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM transaksi WHERE ID_Transaksi = ?");
$stmt->execute([$id]);
$transaksi = $stmt->fetch();

if (!$transaksi) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

$error = "";
$success = "";

// Ambil data user dan paket untuk dropdown
$users = $pdo->query("SELECT ID, CONCAT(COALESCE(First_Name,''),' ',COALESCE(Last_Name,'')) AS Nama FROM user")->fetchAll(PDO::FETCH_ASSOC);
$paket = $pdo->query("SELECT ID_paket, Nama_paket FROM paket")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['update'])) {
    $id = $_POST['id_transaksi'];
    $tanggal_pemesanan = $_POST['tanggal_pemesanan'];
    $total_awal = $_POST['total_awal'];
    $redeem_code = $_POST['redeem_code'];
    $diskon = $_POST['diskon'];
    $total_akhir = $_POST['total_akhir'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $tanggal_dimulai = $_POST['tanggal_dimulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $user_id = $_POST['user_id'];
    $paket_id = $_POST['paket_id'];

    try {
        $stmt = $pdo->prepare("UPDATE transaksi SET Tanggal_Pemesanan = ?, Total_Awal = ?, REDEEM_CODE = ?, Diskon = ?, Total_Akhir = ?, Status_Pembayaran = ?, Metode_Pembayaran = ?, Tanggal_Dimulai = ?, Tanggal_Berakhir = ?, User_ID = ?, Paket_ID_Paket = ? WHERE ID_Transaksi = ?");
        $stmt->execute([$tanggal_pemesanan, $total_awal, $redeem_code, $diskon, $total_akhir, $status_pembayaran, $metode_pembayaran, $tanggal_dimulai, $tanggal_berakhir, $user_id, $paket_id, $id]);
        $success = "Transaksi berhasil diperbarui!";
        // Refresh data
        $transaksi = $pdo->query("SELECT * FROM transaksi WHERE ID_Transaksi = '$id'")->fetch();
    } catch (Exception $e) {
        $error = "Gagal update transaksi: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi - CodingIn</title>
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
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-2xl ml-0 md:ml-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-purple-700">Edit Transaksi</h2>
                <a href="manage_transaksi.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">ID Transaksi</label>
                    <input name="id_transaksi" class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-500" value="<?= htmlspecialchars($transaksi['ID_Transaksi']) ?>" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Pemesanan</label>
                    <input name="tanggal_pemesanan" type="date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Tanggal_Pemesanan']) ?>" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Awal</label>
                        <input name="total_awal" type="number" min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Total_Awal']) ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Redeem Code</label>
                        <input name="redeem_code" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['REDEEM_CODE'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Diskon</label>
                        <input name="diskon" type="number" min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Diskon'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Akhir</label>
                        <input name="total_akhir" type="number" min="0" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Total_Akhir']) ?>" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status Pembayaran</label>
                    <input name="status_pembayaran" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Status_Pembayaran']) ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
                    <input name="metode_pembayaran" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Metode_Pembayaran']) ?>" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Dimulai</label>
                        <input name="tanggal_dimulai" type="date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Tanggal_Dimulai'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Berakhir</label>
                        <input name="tanggal_berakhir" type="date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" value="<?= htmlspecialchars($transaksi['Tanggal_Berakhir'] ?? '') ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">User</label>
                    <select name="user_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                        <option value="">-- Pilih User --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['ID'] ?>" <?= $transaksi['User_ID'] == $u['ID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['Nama']) ?> (<?= htmlspecialchars($u['ID']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Paket</label>
                    <select name="paket_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                        <option value="">-- Pilih Paket --</option>
                        <?php foreach ($paket as $p): ?>
                            <option value="<?= $p['ID_paket'] ?>" <?= $transaksi['Paket_ID_Paket'] == $p['ID_paket'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['Nama_paket']) ?> (<?= htmlspecialchars($p['ID_paket']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button name="update" type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Simpan</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>