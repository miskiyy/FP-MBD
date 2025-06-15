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

// function total_course_user
$stmt_total = $pdo->prepare("SELECT total_course_user(?) AS total");
$stmt_total->execute([$user_id]);
$total = $stmt_total->fetchColumn();

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kursus Saya</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
  <style>
    body {
      background: #f5f5f5;
    }
    .container {
      background: #4c018d;
      color: #fff;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 4px 14px rgb(0 0 0 / 0.4);
    }
    .list-group-item {
      background: #fff;
      color: #4c018d;
      margin-bottom: 10px;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 5px rgb(0 0 0 / 0.2);
      transition: transform 0.2s ease-in-out;
    }
    .list-group-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 20px rgb(0 0 0 / 0.4);
    }
    .list-group-item h5 {
      color: #4c018d;
      font-size: 1.5rem;
      margin-bottom: 0.75rem;
    }
    .list-group-item p {
      color: #555;
      margin-bottom: 0.5rem;
    }
    .alert-info {
      background: #fff3cd;
      color: #856404;
      padding: 20px;
      border-radius: 12px;
    }
    h3 {
      color: #fff;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

  <div class="container mt-5">
    <h3>📚 Kursus Saya</h3>

    <?php if ($courses): ?>
      <ul class="list-group p-0">
        <?php foreach ($courses as $course): ?>
          <li class="list-group-item">
            <h5><?= htmlspecialchars($course['Nama_course']) ?> </h5>
            <p>Rating: <?= htmlspecialchars($course['Rating_course']) ?> | Tingkat Kesulitan: <?= htmlspecialchars($course['Tingkat_kesulitan']) ?> </p>
            <p>Status: <?= $course['Status_course'] ?> </p>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="alert alert-info mt-4">
        Anda tengah mengikuti <?= $total ?> kursus.
      </div>
    <?php else: ?>
      <div class="alert alert-info">Belum ada kursus yang diikuti.</div>
    <?php endif; ?>
  </div>

</body>
</html>
