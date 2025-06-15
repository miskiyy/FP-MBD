<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM transaksi WHERE User_ID = ? ORDER BY Tanggal_Pemesanan DESC");
$stmt->execute([$user_id]);
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Transaksi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Inter', sans-serif; }
    .collapsed { width: 64px !important; padding-left: 0.5rem; padding-right: 0.5rem; }
    .collapsed .sidebar-label { display: none; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
  <?php include '../includes/user_sidebar.php'; ?>

  <main id="mainContent" class="flex-grow p-8 pt-20 transition-all duration-300">
    <a href="../public/dashboard_user.php" class="inline-flex items-center text-purple-700 hover:underline font-semibold mb-6">
      <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Kembali ke Dashboard
    </a>

    <div class="bg-white shadow-lg rounded-xl p-6 max-w-4xl mx-auto">
      <h3 class="text-2xl font-bold text-purple-800 mb-4">💳 Riwayat Transaksi</h3>

      <?php if ($transaksi): ?>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
              <tr>
                <th class="px-4 py-2">Tanggal Pemesanan</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Status</th>
              </tr>
            </thead>
            <tbody class="text-gray-700">
              <?php foreach ($transaksi as $t): ?>
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-2"><?= htmlspecialchars($t['Tanggal_Pemesanan']) ?></td>
                  <td class="px-4 py-2">Rp <?= number_format($t['Total_Akhir'], 0, ',', '.') ?></td>
                  <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded-full text-sm font-medium
                      <?= $t['Status_Pembayaran'] === 'Lunas' ? 'bg-green-100 text-green-800' : 
                           ($t['Status_Pembayaran'] === 'Menunggu Pembayaran' ? 'bg-yellow-100 text-yellow-800' : 
                           'bg-blue-100 text-blue-800') ?>">
                      <?= htmlspecialchars($t['Status_Pembayaran']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="bg-yellow-50 text-yellow-800 px-4 py-3 rounded-md font-medium">
          Belum ada transaksi.
        </div>
      <?php endif; ?>
    </div>
  </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>