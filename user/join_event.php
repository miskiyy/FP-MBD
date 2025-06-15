<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$event_id = $_POST['event_id'] ?? $_GET['id'] ?? null;

if (!$event_id) {
    $error = "ID event tidak ditemukan.";
} else {
    // Validasi event tersedia
    $checkEvent = $pdo->prepare("SELECT * FROM event WHERE ID_event = ?");
    $checkEvent->execute([$event_id]);
    $event = $checkEvent->fetch();

    if (!$event) {
        $error = "Event tidak ditemukan.";
    } else {
        // Cek apakah user sudah mendaftar
        $cek = $pdo->prepare("SELECT 1 FROM user_event WHERE User_ID = ? AND Event_ID_Event = ?");
        $cek->execute([$user_id, $event_id]);

        if ($cek->fetch()) {
            $error = "Kamu sudah mendaftar di event ini.";
        } else {
            // Simpan pendaftaran
            $insert = $pdo->prepare("INSERT INTO user_event (User_ID, Event_ID_Event) VALUES (?, ?)");
            $insert->execute([$user_id, $event_id]);

            $success = "Berhasil mendaftar ke event.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Gabung Event</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Inter', sans-serif; }
    .collapsed { width: 64px !important; padding-left: 0.5rem; padding-right: 0.5rem; }
    .collapsed .sidebar-label { display: none; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="flex">
    <?php include '../includes/user_sidebar.php'; ?>

    <main id="mainContent" class="flex-grow p-8 pt-20 transition-all duration-300">
      <div class="max-w-xl mx-auto bg-white shadow-lg rounded-xl p-8 text-center">
        <h1 class="text-2xl font-bold text-purple-800 mb-6">Gabung Event</h1>

        <?php if (isset($error)): ?>
          <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded mb-4"><?= htmlentities($error) ?></div>
          <a href="event_list.php" class="inline-block mt-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold">Kembali</a>
        <?php elseif (isset($success)): ?>
          <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4"><?= htmlentities($success) ?></div>
          <a href="event_list.php" class="inline-block mt-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold">Lihat Event Lain</a>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>lucide.createIcons();</script>
</body>
</html>