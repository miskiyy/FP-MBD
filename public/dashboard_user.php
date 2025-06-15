<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Ambil nama depan user
$stmtUser = $pdo->prepare("SELECT First_Name FROM user WHERE ID = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Cek transaksi terbaru user
$stmt = $pdo->prepare("SELECT ID_Transaksi, Status_Pembayaran, Tanggal_Berakhir 
                       FROM transaksi 
                       WHERE User_ID = ?
                       ORDER BY Tanggal_Pemesanan DESC LIMIT 1");
$stmt->execute([$user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Belum pernah transaksi → redirect ke plan
if (!$data) {
    header("Location: ../transaksi/choose_plan.php");
    exit();
}

// Masih nunggu pembayaran / bukti → redirect ke status
if (in_array($data['Status_Pembayaran'], ['Menunggu Pembayaran', 'Menunggu Verifikasi'])) {
    header("Location: ../transaksi/status_transaksi.php?id=" . $data['ID_Transaksi']);
    exit();
}

// Sudah lunas tapi masa aktifnya habis → redirect ke plan
if ($data['Status_Pembayaran'] === 'Lunas' && strtotime($data['Tanggal_Berakhir']) < time()) {
    header("Location: ../transaksi/choose_plan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard User - CodingIn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen relative">

  <!-- Wrapper Flex: sidebar + content -->
  <div class="flex">
    <?php include '../includes/user_sidebar.php'; ?>

    <main id="mainContent" class="flex-1 p-8 pt-20 transition-all duration-300">
      <h2 class="text-4xl font-bold text-center text-purple-700 mb-6">Dashboard User</h2>

      <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Selamat datang di <span class="text-purple-700 font-semibold"><?= htmlspecialchars($user["First_Name"]) ?></span> 😊 </h3>
        <p class="text-gray-600">Di sini kamu bisa melihat course, event, challenge, dan sertifikatmu. Semangat belajar di CodingIn!</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="../user/my_certificate.php" class="bg-purple-100 hover:bg-purple-200 text-purple-800 font-semibold px-4 py-3 rounded text-center">🎓 My Certificate</a>
        <a href="../user/my_courses.php" class="bg-green-100 hover:bg-green-200 text-green-800 font-semibold px-4 py-3 rounded text-center">📚 My Courses</a>
        <a href="../user/my_event.php" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold px-4 py-3 rounded text-center">🎉 My Event</a>
        <a href="../user/my_challenge.php" class="bg-pink-100 hover:bg-pink-200 text-pink-800 font-semibold px-4 py-3 rounded text-center">🏆 My Challenge</a>
        <a href="../user/event_list.php" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-4 py-3 rounded text-center">🗓️ Lihat Semua Event</a>
        <a href="../user/course_list.php" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-4 py-3 rounded text-center">📘 Lihat Semua Course</a>
        <a href="../user/challenge_list.php" class="bg-pink-50 hover:bg-pink-100 text-pink-700 font-semibold px-4 py-3 rounded text-center">🏁 Lihat Semua Challenge</a>
        <a href="../user/transactions.php" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-semibold px-4 py-3 rounded text-center">💳 Transaction History</a>
      </div>
    </main>
  </div>

  <script>lucide.createIcons();</script>

</body>
</html>