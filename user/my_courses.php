<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';

$user_id = $_SESSION["user_id"];

// Stored Procedure GetUserCourses
$stmt = $pdo->prepare("CALL GetUserCourses(?)");

$stmt->execute([$user_id]);

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// M function total_course_user
$stmt_total = $pdo->prepare("SELECT total_course_user(?) AS total");
$stmt_total->execute([$user_id]);
$total = $stmt_total->fetchColumn();

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Kursus Saya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

  <div class="flex">
    <?php include '../includes/user_sidebar.php'; ?>

    <!-- Main Content -->
    <main id="mainContent" class="flex-grow p-8 pt-20 transition-all duration-300">
      <a href="../public/dashboard_user.php" class="inline-flex items-center text-purple-700 hover:underline mb-6 font-medium">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Kembali ke Dashboard
      </a>

      <div class="bg-purple-800 text-white rounded-2xl shadow-lg p-8">
        <h3 class="text-2xl font-bold mb-6">📚 Kursus Saya</h3>

        <?php if ($courses): ?>
          <div class="space-y-4">
            <?php foreach ($courses as $course): ?>
              <div class="bg-white text-purple-900 rounded-xl shadow p-6 transition hover:shadow-lg hover:-translate-y-1 duration-200">
                <h5 class="text-xl font-bold mb-2"><?= htmlspecialchars($course['Nama_course']) ?></h5>
                <p class="text-sm text-gray-600 mb-1">⭐ Rating: <?= htmlspecialchars($course['Rating_course']) ?> | 🎯 Tingkat Kesulitan: <?= htmlspecialchars($course['Tingkat_kesulitan']) ?></p>
                <p class="text-sm text-gray-700 font-semibold">Status: <?= $course['Status_course'] ?></p>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="mt-6 bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg font-medium">
            Anda tengah mengikuti <?= $total ?> kursus.
          </div>
        <?php else: ?>
          <div class="mt-6 bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg font-medium">
            Belum ada kursus yang diikuti.
          </div>
        <?php endif; ?>
      </div>
    </main>
  </div>
  <script>lucide.createIcons();</script>
</body>
</html>