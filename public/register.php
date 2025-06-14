<?php
require_once '../config/database.php';
session_start();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = uniqid();
    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $gender = $_POST["jenis_kelamin"];
    $pekerjaan = $_POST["pekerjaan"];
    $kota = $_POST["kota"];
    $negara = $_POST["negara"];
    $telepon = $_POST["nomor_telepon"];
    $email = $_POST["email"];
    $tentang = $_POST["tentang_saya"];
    $lahir = $_POST["tanggal_lahir"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE Email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email sudah digunakan.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO user 
                (ID, First_Name, Last_Name, Jenis_kelamin, Pekerjaan, Kota, Negara, Nomor_Telepon, Email, Tentang_Saya, Tanggal_Lahir, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $result = $stmt->execute([
                $id, $first, $last, $gender, $pekerjaan, $kota, $negara, $telepon, $email, $tentang, $lahir, $password
            ]);

            if ($result) {
                $success = "Registrasi berhasil! Silakan <a href='login.php' class='text-purple-700 underline'>login</a>.";
            } else {
                $error = "Gagal menyimpan data.";
            }
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<main class="flex-1 flex items-center justify-center py-12 px-4">
  <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center text-purple-700 mb-2">Registrasi CodingIn</h2>
    <p class="text-center text-gray-500 mb-6 text-sm">Daftar untuk mulai belajar dan ikut event seru!</p>
    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">First Name</label>
          <input type="text" name="first_name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Last Name</label>
          <input type="text" name="last_name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Jenis Kelamin (L/P)</label>
          <input type="text" name="jenis_kelamin" maxlength="1" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Pekerjaan</label>
          <input type="text" name="pekerjaan" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Kota</label>
          <input type="text" name="kota" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Negara</label>
          <input type="text" name="negara" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
        <input type="text" name="nomor_telepon" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Tentang Saya</label>
        <textarea name="tentang_saya" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200"></textarea>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Password</label>
          <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
          <input type="password" name="confirm" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
        </div>
      </div>
      <button type="submit" class="w-full bg-purple-700 text-white py-2 rounded-full font-semibold hover:bg-purple-800 transition">Daftar</button>
    </form>
    <div class="text-center mt-4 text-sm">
      Sudah punya akun?
      <a href="login.php" class="text-purple-700 font-semibold hover:underline">Login di sini</a>
    </div>
  </div>
</main>
<?php include '../includes/footer.php'; ?>