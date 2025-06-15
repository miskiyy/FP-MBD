<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$stmt = $pdo->query("SELECT * FROM courses ORDER BY Rating_course DESC");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar Course</title>
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
    <h2 class="text-2xl font-bold text-purple-800 mb-6 text-center">📚 Daftar Course Tersedia</h2>

    <?php if (isset($_GET['status'])): ?>
      <div class="max-w-xl mx-auto mb-6 px-4 py-3 rounded-lg text-sm font-medium 
        <?= $_GET['status'] === 'joined' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
        <?= $_GET['status'] === 'joined' ? 'Berhasil bergabung dengan course!' : 'Kamu sudah terdaftar di course ini.' ?>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($courses as $course): ?>
        <div class="bg-white rounded-xl shadow p-6 transition hover:shadow-lg hover:-translate-y-1 duration-200">
          <h5 class="text-lg font-bold text-purple-800 mb-2"><?= htmlspecialchars($course['Nama_course']) ?></h5>
          <p class="text-sm text-gray-700 mb-1">🎯 Tingkat Kesulitan: <strong><?= htmlspecialchars($course['Tingkat_kesulitan']) ?></strong></p>
          <p class="text-sm text-gray-700 mb-4">⭐ Rating: <?= htmlspecialchars($course['Rating_course']) ?>/5</p>
          <a href="join_course.php?id=<?= urlencode($course['ID_courses']) ?>" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-md transition">
            Gabung Sekarang
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-10">
      <a href="../public/dashboard_user.php" class="inline-flex items-center text-purple-700 hover:underline font-semibold">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Kembali ke Dashboard
      </a>
    </div>
  </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>