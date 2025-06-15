<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM user WHERE ID = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Proses update data user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $telepon = $_POST['nomor_telepon'];
    $kota = $_POST['kota'];
    $tentang = $_POST['tentang_saya'];
    
    // Upload foto jika ada
    $fotoPath = $user['Foto_Profil'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoPath = 'uploads/profile_' . $user_id . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../' . $fotoPath);
    }

    $update = $pdo->prepare("UPDATE user SET First_Name=?, Last_Name=?, Email=?, Nomor_Telepon=?, Kota=?, Tentang_Saya=?, Foto_Profil=? WHERE ID=?");
    $update->execute([$first, $last, $email, $telepon, $kota, $tentang, $fotoPath, $user_id]);

    header("Location: edit_profil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil - CodingIn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="flex">
    <?php include '../includes/user_sidebar.php'; ?>

    <main id="mainContent" class="flex-1 p-8 pt-20 transition-all duration-300">
      <div class="max-w-3xl w-full mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-purple-700 mb-6">Profil</h2>

        <!-- FOTO PROFIL -->
        <div class="flex items-center mb-6 gap-4">
          <img src="../<?= $user['Foto_Profil'] ?? 'assets/default.png' ?>" alt="Foto Profil" class="w-20 h-20 rounded-full object-cover border-2 border-purple-700">
          <div>
            <h3 class="text-xl font-semibold"><?= htmlspecialchars($user['First_Name']) ?> <?= htmlspecialchars($user['Last_Name']) ?></h3>
            <p class="text-gray-600"><?= htmlspecialchars($user['Email']) ?></p>
          </div>
        </div>

        <!-- TOMBOL EDIT -->
        <button id="editBtn" class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded mb-4 font-semibold">
          Edit Profil
        </button>

        <!-- FORM -->
        <form method="POST" enctype="multipart/form-data" id="editForm" class="space-y-4 hidden">
          <div>
            <label class="block text-gray-700 font-semibold mb-1">Nama Depan</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($user['First_Name']) ?>" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-purple-500" required>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Nama Belakang</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($user['Last_Name']) ?>" class="w-full border border-gray-300 rounded px-4 py-2">
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" class="w-full border border-gray-300 rounded px-4 py-2" required>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" value="<?= htmlspecialchars($user['Nomor_Telepon']) ?>" class="w-full border border-gray-300 rounded px-4 py-2">
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Kota</label>
            <input type="text" name="kota" value="<?= htmlspecialchars($user['Kota']) ?>" class="w-full border border-gray-300 rounded px-4 py-2">
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Tentang Saya</label>
            <textarea name="tentang_saya" rows="3" class="w-full border border-gray-300 rounded px-4 py-2"><?= htmlspecialchars($user['Tentang_Saya']) ?></textarea>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Foto Profil (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="block text-sm text-gray-500">
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded font-semibold">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('editBtn').addEventListener('click', () => {
      document.getElementById('editForm').classList.remove('hidden');
      document.getElementById('editBtn').classList.add('hidden');
    });
    lucide.createIcons();
  </script>
</body>
</html>