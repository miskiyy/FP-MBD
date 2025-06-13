<?php
require_once '../config/database.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Cek di tabel user
    $stmtUser = $pdo->prepare("SELECT * FROM user WHERE Email = ? AND password = ?");
    $stmtUser->execute([$email, $password]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // Cek di tabel karyawan
    $stmtKaryawan = $pdo->prepare("SELECT * FROM karyawan WHERE Email = ? AND password = ?");
    $stmtKaryawan->execute([$email, $password]);
    $karyawan = $stmtKaryawan->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['role'] = 'user';
        $_SESSION['name'] = $user['First_Name'];
        $_SESSION['user_id'] = $user['ID'];
        header("Location: dashboard_user.php");
        exit();
    } elseif ($karyawan) {
        $_SESSION['role'] = 'karyawan';
        $_SESSION['name'] = $karyawan['First_Name'];
        $_SESSION['NIK'] = $karyawan['NIK'];
        header("Location: dashboard_admin.php");
        exit();
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4">
        <h3 class="text-center mb-4">Login</h3>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>

        <div class="text-center mt-3">
          <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
