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
  <h3 class="mb-4">🎉 Event Saya</h3>
  <?php if ($events): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Tanggal</th>
          <th>Lokasi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $event): ?>
          <tr>
            <td><?= htmlspecialchars($event['Nama_Event']) ?></td>
            <td><?= htmlspecialchars($event['tanggal_mulai_event']) ?></td>
            <td><?= htmlspecialchars($event['Lokasi_Acara']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-secondary">Kamu belum mengikuti event manapun.</div>
  <?php endif; ?>
</div>

<?php include '../includes/footer_user.php'; ?>
