<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");

    exit();
}

$user_id = $_SESSION["user_id"];

$course_id = $_GET['id'] ?? null;

if (!$course_id) {
    $error = "ID kursus tidak ditemukan.";
} else {
    // Cek apakah course ada
    $checkCourse = $pdo->prepare("SELECT * FROM courses WHERE ID_courses = ?");
    $checkCourse->execute([$course_id]);

    if (!$checkCourse->fetch()) {
        $error = "Kursus tidak ditemukan.";
    } else {
        // Cek apakah user sudah bergabung
        $cek = $pdo->prepare("SELECT 1 FROM user_course WHERE User_ID = ? AND Courses_ID_Courses = ?");
        $cek->execute([$user_id, $course_id]);

        if ($cek->fetch()) {
            $error = "Anda sudah bergabung di kursus ini.";
        } else {
            // Gabung ke kursus
            $insert = $pdo->prepare("INSERT INTO user_course (User_ID, Courses_ID_Courses) VALUES (?, ?)");
            $insert->execute([$user_id, $course_id]);

            $success = "Berhasil bergabung ke kursus.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Gabung Kursus</title>
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
    <!-- Sidebar (cukup include aja, tidak usah bikin burger lagi) -->
    <?php include '../includes/user_sidebar.php'; ?>

    <!-- Main content -->
    <main id="mainContent" class="flex-grow p-8 pt-20 transition-all duration-300">
      <div class="max-w-xl mx-auto bg-white shadow-lg rounded-xl p-8 text-center">
        <h1 class="text-2xl font-bold text-purple-800 mb-6">Gabung Kursus</h1>

        <?php if (isset($error)): ?>
          <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4"><?= htmlentities($error) ?></div>
          <a href="course_list.php" class="inline-block mt-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold">Kembali</a>
        <?php elseif (isset($success)): ?>
          <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4"><?= htmlentities($success) ?></div>
          <a href="course_list.php" class="inline-block mt-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold">Lihat Daftar Kursus</a>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>
    lucide.createIcons(); 
  </script>
</body>
</html>
