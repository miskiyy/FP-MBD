<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<?php include '../includes/header_user.php'; ?>

<div class="container py-5">
  <div class="card shadow-sm rounded-4 p-4 text-center">
    <h3 class="mb-3 fw-semibold">🎯 Dashboard User</h3>
    <p class="mb-4">Hai user, selamat datang: <strong><?= htmlspecialchars($_SESSION["user_id"]) ?></strong></p>

    <div class="row justify-content-center g-3">
      <div class="col-md-6 col-lg-4">
        <a href="../user/my_certificate.php" class="btn btn-outline-primary w-100 py-2">🎓 My Certificate</a>
      </div>
      <div class="col-md-6 col-lg-4">
        <a href="../user/my_courses.php" class="btn btn-outline-success w-100 py-2">📚 My Courses</a>
      </div>
      <div class="col-md-6 col-lg-4">
        <a href="../user/my_event.php" class="btn btn-outline-info w-100 py-2">🎉 My Event</a>
      </div>
      <div class="col-md-6 col-lg-4">
        <a href="../user/event_list.php" class="btn btn-outline-secondary w-100 py-2">🗓️ Lihat Semua Event</a>
      </div>
      <div class="col-md-6 col-lg-4">
        <a href="../user/course_list.php" class="btn btn-outline-secondary w-100 py-2">🗓️ Lihat Semua Course</a>
      </div>
      <div class="col-md-6 col-lg-4">
        <a href="../user/transactions.php" class="btn btn-outline-warning w-100 py-2">💳 Transaction History</a>
      </div>
    </div>

    <a href="logout.php" class="btn btn-danger mt-4 px-4">🚪 Logout</a>
  </div>
</div>

<?php include '../includes/footer_user.php'; ?>
