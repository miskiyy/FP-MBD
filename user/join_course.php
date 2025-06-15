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
  <meta charset="UTF-8">
  <title>Gabung Kursus</title>
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
      margin-top: 50px;
      max-width: 500px;
      text-align: center;
    }
    .alert {
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
    }
    .alert-info {
      background: #fff3cd;
      color: #856404;
    }
    .alert-danger {
      background: #f8d7da;
      color: #721c24;
    }
    .alert-success {
      background: #d4edda;
      color: #155724;
    }
    a.btn {
      margin-bottom: 20px;
      padding: 10px 20px;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: bold;
      background: #fff;
      color: #4c018d;
      text-decoration: none;
      box-shadow: 0 2px 5px rgb(0 0 0 / 0.4);
      transition: transform 0.2s ease-in-out;
    }
    a.btn:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 20px rgb(0 0 0 / 0.4);
      background: #e5ccff;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Gabung Kursus</h1>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlentities($error) ?> </div>
      <a href="course_list.php" class="btn">Kembali</a>
    <?php endif; ?>
  
    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?= htmlentities($success) ?> </div>
      <a href="course_list.php" class="btn">Lihat Daftar Kursus</a>
    <?php endif; ?>
  </div>

  <?php include '../includes/footer_user.php'; ?>

</body>
</html>
