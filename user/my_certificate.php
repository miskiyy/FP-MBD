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
  SELECT su.Sertifikat_ID_Sertifikat, s.Nama_Sertifikat, s.sertif_template
  FROM sertifuser su
  JOIN sertifikat s ON su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
  WHERE su.User_ID = ?
");
$stmt->execute([$user_id]);
$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
  <h3 class="mb-4">🎓 Sertifikat Saya</h3>
  <?php if (count($certificates) > 0): ?>
    <ul class="list-group">
      <?php foreach ($certificates as $cert): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($cert['Nama_Sertifikat']) ?> (<?= htmlspecialchars($cert['Sertifikat_ID_Sertifikat']) ?>)
          <a href="/FP-MBD-main/uploads/sertif/<?= htmlspecialchars($cert['sertif_template']) ?>" class="btn btn-sm btn-success" target="_blank">Lihat</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-warning">Belum ada sertifikat.</div>
  <?php endif; ?>
</div>


<?php include '../includes/footer.php'; ?>
