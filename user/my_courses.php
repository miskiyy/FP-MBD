<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';
include '../includes/header.php';

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
  SELECT c.Nama_course, c.Rating_course, c.Tingkat_kesulitan, uc.Status_course
  FROM user_course uc
  JOIN courses c ON uc.Courses_ID_Courses = c.ID_courses
  WHERE uc.User_ID = ?
");
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
  <h3 class="mb-4">📚 Kursus Saya</h3>
  <?php if ($courses): ?>
    <div class="list-group">
      <?php foreach ($courses as $course): ?>
        <div class="list-group-item">
          <h5><?= htmlspecialchars($course['Nama_course']) ?></h5>
          <p>Rating: <?= htmlspecialchars($course['Rating_course']) ?> |
             Tingkat Kesulitan: <?= htmlspecialchars($course['Tingkat_kesulitan']) ?></p>
          <p>Status: <?= $course['Status_course'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">Belum ada kursus yang diikuti.</div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
