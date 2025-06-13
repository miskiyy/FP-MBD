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
  <meta charset="UTF-8">
  <title>Daftar Course</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      transition: all 0.3s ease-in-out;
    }
    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="mb-4 text-center">📚 Daftar Course Tersedia</h2>

    <?php if (isset($_GET['status'])): ?>
      <div class="alert alert-<?= $_GET['status'] === 'joined' ? 'success' : 'info' ?> text-center">
        <?= $_GET['status'] === 'joined' ? 'Berhasil bergabung dengan course!' : 'Kamu sudah terdaftar di course ini.' ?>
      </div>
    <?php endif; ?>

    <div class="row">
      <?php foreach ($courses as $course): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($course['Nama_course']) ?></h5>
              <p class="card-text">Tingkat Kesulitan: <strong><?= htmlspecialchars($course['Tingkat_kesulitan']) ?></strong></p>
              <p class="mb-1"><strong>Rating:</strong> <?= htmlspecialchars($course['Rating_course']) ?>/5</p>
              <a href="join_course.php?id=<?= urlencode($course['ID_courses']) ?>" class="btn btn-success w-100 mt-3">
                Gabung Sekarang
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
      <a href="../dashboard_user.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
