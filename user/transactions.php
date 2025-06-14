<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';
include '../includes/header_user.php';

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM transaksi WHERE User_ID = ? ORDER BY Tanggal_Pemesanan DESC");
$stmt->execute([$user_id]);
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
  <h3 class="mb-4">💳 Riwayat Transaksi</h3>
  <?php if ($transaksi): ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Tanggal Pemesanan</th>
          <th>Total</th>
          <th>Status</th>
          <th>Metode</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transaksi as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['Tanggal_Pemesanan']) ?></td>
            <td>Rp <?= number_format($t['Total_Akhir'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($t['Status_Pembayaran']) ?></td>
            <td><?= htmlspecialchars($t['Metode_Pembayaran']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-light">Belum ada transaksi.</div>
  <?php endif; ?>
</div>

<?php include '../includes/footer_user.php'; ?>
