<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$stmt = $pdo->query("SELECT * FROM event ORDER BY tanggal_mulai_event ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Event</title>
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
    <h2 class="mb-4 text-center">📅 Daftar Event Tersedia</h2>
    <div class="row">
      <?php foreach ($events as $event): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($event['Nama_Event']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($event['Deskripsi_Event']) ?></p>
              <p class="mb-1"><strong>Lokasi:</strong> <?= htmlspecialchars($event['Lokasi_Acara']) ?></p>
              <p class="mb-1"><strong>Tanggal:</strong> <?= htmlspecialchars($event['tanggal_mulai_event']) ?> s/d <?= htmlspecialchars($event['tanggal_berakhir_event']) ?></p>
              <form action="join_event.php" method="post">
                <input type="hidden" name="event_id" value="<?= $event['ID_event'] ?>">
                <button class="btn btn-primary w-100 mt-3">Daftar Sekarang</button>
              </form>
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
