<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
  SELECT e.Nama_Event, e.tanggal_mulai_event, e.Lokasi_Acara
  FROM user_event ue
  JOIN event e ON ue.Event_ID_Event = e.ID_event
  WHERE ue.User_ID = ?
");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Event Saya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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

    <div class="p-4">
        <a href="../public/dashboard_user.php" class="inline-flex items-center text-purple-700 hover:underline font-semibold">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Kembali ke Dashboard
        </a>
    </div>
    <h2 class="text-2xl font-bold text-purple-800 mb-6">🎉 Daftar Event Saya</h2>

    <?php if ($events): ?>
      <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead class="bg-purple-100 text-purple-800 uppercase text-xs">
              <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Lokasi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php foreach ($events as $event): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($event['Nama_Event']) ?></td>
                  <td class="px-6 py-4"><?= date("d F Y", strtotime($event['tanggal_mulai_event'])) ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($event['Lokasi_Acara']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php else: ?>
      <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg font-medium mb-4">
        Kamu <strong>belum</strong> mengikuti event apapun.<br>Silakan lihat daftar event dan bergabung! 🌟
      </div>
      <a href="../public/dashboard_user.php" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2 rounded-lg">↥ Kembali ke Dashboard</a>
    <?php endif; ?>
  </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>