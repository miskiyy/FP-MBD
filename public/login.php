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
    <!-- Login Form -->
    <main class="flex-1 flex items-center justify-center py-12 px-4">
      <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-purple-700 mb-2">Login ke CodingIn</h2>
        <p class="text-center text-gray-500 mb-6 text-sm">Masuk untuk mulai belajar dan ikut event seru!</p>
        <?php if ($error): ?>
          <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
          </div>
          <button type="submit" class="w-full bg-purple-700 text-white py-2 rounded-full font-semibold hover:bg-purple-800 transition">Masuk</button>
        </form>
        <div class="text-center mt-4 text-sm">
          Belum punya akun?
          <a href="register.php" class="text-purple-700 font-semibold hover:underline">Daftar di sini</a>
        </div>
      </div>
    </main>
<?php include '../includes/footer.php'; ?>