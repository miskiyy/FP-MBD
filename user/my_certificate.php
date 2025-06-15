<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
  SELECT su.Sertifikat_ID_Sertifikat, s.Nama_Sertifikat, s.sertif_template
  FROM sertifuser su
  JOIN sertifikat s ON su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
  WHERE su.User_ID = ?
");
$stmt->execute([$user_id]);
$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sertifikat Saya - CodingIn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">

  <div class="flex">
    <?php include '../includes/user_sidebar.php'; ?>

    <main id="mainContent" class="flex-1 p-8 pt-20 transition-all duration-300">
      <a href="../public/dashboard_user.php" class="inline-flex items-center text-purple-700 hover:underline mb-4">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Kembali ke Dashboard
      </a>
      
      <h2 class="text-3xl font-bold text-purple-700 mb-6">🎓 Sertifikat Saya</h2>

      <?php if (count($certificates) > 0): ?>
        <ul class="space-y-4">
          <?php foreach ($certificates as $cert): ?>
            <li class="bg-white rounded-lg shadow p-4 flex justify-between items-center">
              <div>
                <p class="font-semibold text-gray-800"><?= htmlspecialchars($cert['Nama_Sertifikat']) ?></p>
                <p class="text-sm text-gray-500">ID: <?= htmlspecialchars($cert['Sertifikat_ID_Sertifikat']) ?></p>
              </div>
              <a href="/FP-MBD-main/uploads/sertif/<?= htmlspecialchars($cert['sertif_template']) ?>"
                 target="_blank"
                 class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm font-semibold">
                Lihat
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="bg-yellow-100 text-yellow-800 font-medium px-4 py-3 rounded">
          Belum ada sertifikat.
        </div>
      <?php endif; ?>
    </main>
  </div>

  <script>lucide.createIcons();</script>
</body>
</html>