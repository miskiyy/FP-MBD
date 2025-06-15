<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");

    exit();
}

require_once '../config/database.php';
include '../includes/header_user.php';

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
  SELECT e.Nama_Event, e.tanggal_mulai_event, e.Lokasi_Acara
  FROM user_event ue
  JOIN event e ON ue.Event_ID_Event = e.ID_event
  WHERE ue.User_ID = ?
");

$stmt->execute([$user_id]);

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
  <h3 class="mb-4">🎉 Daftar Event Saya</h3>

  <?php if ($events): ?>
    <div class="card p-4 shadow rounded-4">
      <h5 class="card-title">Event yang Saya Ikuti</h5>
      <table class="table table-hover mt-3 align-middle">
        <thead class="table-light">
          <tr>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Lokasi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($events as $event): ?>
            <tr>
              <td><?= htmlspecialchars($event['Nama_Event']) ?> </td>
              <td><?= date("d F Y", strtotime($event['tanggal_mulai_event'])) ?> </td>
              <td><?= htmlspecialchars($event['Lokasi_Acara']) ?> </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <a href="../public/dashboard_user.php" class="btn btn-secondary mt-4">↥ Kembali ke Dashboard</a>
    </div>
  <?php else: ?>
    <div class="alert alert-info rounded-4 p-4">
      Kamu <strong>belum</strong> mengikuti event apapun.
      <br>Silakan lihat daftar event dan bergabung! 🌟
    </div>
    <a href="../public/dashboard_user.php" class="btn btn-secondary mt-4">↥ Kembali ke Dashboard</a>
  <?php endif; ?>
</div>

<?php include '../includes/footer_user.php'; ?>
