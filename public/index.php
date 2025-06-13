<?php
session_start();

if (isset($_SESSION["role"]) && $_SESSION["role"] === "karyawan") {
    header("Location: dashboard_admin.php");
    exit();
} elseif (isset($_SESSION["user_id"])) {
    header("Location: dashboard_user.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5 text-center">
  <h1 class="mb-4">Selamat Datang di CodingIn</h1>
  <p class="lead">Silakan login untuk melanjutkan.</p>
  <a href="login.php" class="btn btn-primary btn-lg">🔐 Login</a>
</div>

<?php include '../includes/footer.php'; ?>
