<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';

$user_id = $_SESSION["user_id"];

// Ambil challenge yang diikuti user
$stmt = $pdo->prepare("
  SELECT c.Nama_Challenge, c.Deskripsi, c.Tanggal_mulai, c.Tanggal_berakhir
  FROM challenge_user cu
  JOIN challenge c ON cu.challenge_id_challenge = c.id_challenge
  WHERE cu.User_ID = ?
  ORDER BY c.Tanggal_mulai ASC
");
$stmt->execute([$user_id]);
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total challenge
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM challenge_user WHERE User_ID = ?");
$stmt_total->execute([$user_id]);
$total = $stmt_total->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Challenge Saya</title>
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
      <a href="../public/dashboard_user.php" class="inline-flex items-center text-purple-700 hover:underline mb-6 font-medium">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Kembali ke Dashboard
      </a>

      <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-2xl font-bold text-purple-800 mb-4">🏆 Challenge Saya</h2>

        <?php if ($challenges): ?>
          <div class="space-y-4">
            <?php foreach ($challenges as $c): ?>
              <div class="bg-gray-50 p-4 rounded-xl shadow hover:shadow-md transition">
                <h4 class="text-lg font-semibold text-purple-700"><?= htmlspecialchars($c['Nama_Challenge']) ?></h4>
                <p class="text-sm text-gray-700 mb-1"><?= htmlspecialchars($c['Deskripsi']) ?></p>
                <p class="text-sm text-gray-600"><strong>🗓️ Tanggal:</strong> <?= htmlspecialchars($c['Tanggal_mulai']) ?> s/d <?= htmlspecialchars($c['Tanggal_berakhir']) ?></p>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="mt-6 bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg font-medium">
            Kamu mengikuti <?= $total ?> challenge.
          </div>
        <?php else: ?>
          <div class="bg-yellow-50 text-yellow-700 px-4 py-3 rounded-lg">
            Belum ada challenge yang kamu ikuti. Yuk ikutan dan tingkatkan skill-mu! 💪
          </div>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>lucide.createIcons();</script>
</body>
</html>