<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "karyawan") {
    header("Location: login.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container py-5">
  <div class="card shadow-sm rounded-4 p-4">
    <h3 class="text-center mb-3 fw-semibold">🛠️ Dashboard Admin</h3>
    <p class="text-center">Halo admin, selamat datang: <strong><?= htmlspecialchars($_SESSION["NIK"]) ?></strong></p>

    <div class="list-group list-group-flush mt-4">
      <a href="../admin/usermanage/manage_user.php" class="list-group-item list-group-item-action">🔧 Manage User</a>
      <a href="../admin/paketmanage/manage_paket.php" class="list-group-item list-group-item-action">📦 Manage Paket</a>
      <a href="../admin/coursemanage/manage_course.php" class="list-group-item list-group-item-action">📚 Manage Course</a>
      <a href="../admin/eventmanage/manage_event.php" class="list-group-item list-group-item-action">🎉 Manage Event</a>
      <a href="../admin/sertifikatmanage/manage_sertifikat.php" class="list-group-item list-group-item-action">🏆 Manage Sertifikat</a>
      <a href="../admin/transaksimanage/manage_transaction.php" class="list-group-item list-group-item-action">💵 Manage Transaksi</a>
      <a href="logout.php" class="list-group-item list-group-item-action text-danger">🚪 Logout</a>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
