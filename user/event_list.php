<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $pdo->query("
    SELECT e.*, k.First_Name AS Nama_Karyawan 
    FROM event e
    JOIN karyawan k ON e.Karyawan_NIK = k.NIK
    ORDER BY e.tanggal_mulai_event ASC
");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar Event</title>
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
      <h2 class="text-2xl font-bold text-purple-800 mb-6 text-center">📅 Daftar Event Tersedia</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($events as $event): ?>
          <div class="bg-white rounded-xl shadow hover:shadow-lg p-6 transition duration-200">
            <h5 class="text-lg font-semibold text-purple-900 mb-2"><?= htmlspecialchars($event['Nama_Event']) ?></h5>
            <p class="text-gray-700 text-sm mb-2"><?= htmlspecialchars($event['Deskripsi_Event']) ?></p>

            <div class="flex flex-col gap-2 mt-4">
              <button onclick='showModal(<?= json_encode([
                  'nama' => $event['Nama_Event'],
                  'jenis' => $event['Jenis_Event'],
                  'deskripsi' => $event['Deskripsi_Event'],
                  'lokasi' => $event['Lokasi_Acara'],
                  'mulai' => $event['tanggal_mulai_event'],
                  'selesai' => $event['tanggal_berakhir_event'],
                  'pengajar' => $event['Nama_Karyawan'] ?? '-'
              ]) ?>)' 
              class="bg-white border border-purple-600 text-purple-600 font-medium rounded px-4 py-2 hover:bg-purple-100 transition text-sm">
                Detail
              </button>

              <form action="join_event.php" method="post">
                <input type="hidden" name="event_id" value="<?= $event['ID_event'] ?>">
                <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-md font-semibold text-sm">
                  Daftar Sekarang
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="text-center mt-10">
        <a href="../public/dashboard_user.php" class="inline-flex items-center text-purple-700 hover:underline font-semibold">
          <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Kembali ke Dashboard
        </a>
      </div>
    </main>
  </div>

  <!-- MODAL DETAIL -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">
    <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
      <i data-lucide="x" class="w-5 h-5"></i>
    </button>
    <h3 class="text-xl font-bold text-purple-700 mb-2" id="modalNamaEvent">Study Jam UI/UX</h3>
    <p class="text-sm text-gray-700 mb-2"><span class="font-medium">Jenis:</span> <span id="modalJenisEvent"></span></p>
    <p class="text-gray-600 text-sm mb-4" id="modalDeskripsi"></p>
    <p class="text-sm text-gray-700 mb-1">
      <i data-lucide="map-pin" class="inline w-4 h-4 mr-1 text-red-500"></i> <span id="modalLokasi"></span>
    </p>
    <p class="text-sm text-gray-700 mb-1">
      <i data-lucide="calendar" class="inline w-4 h-4 mr-1 text-indigo-500"></i> <span id="modalTanggal"></span>
    </p>
    <p class="text-sm text-gray-700">
      <i data-lucide="user" class="inline w-4 h-4 mr-1 text-purple-600"></i> <span id="modalPengajar"></span>
    </p>
  </div>
</div>


  <script>
    lucide.createIcons();

    const modal = document.getElementById('eventModal');
    const closeBtn = document.getElementById('closeModal');

    closeBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    function showModal(data) {
      document.getElementById('modalNamaEvent').textContent = data.nama;
      document.getElementById('modalJenisEvent').textContent = data.jenis;
      document.getElementById('modalDeskripsi').textContent = data.deskripsi;
      document.getElementById('modalLokasi').textContent = data.lokasi;
      document.getElementById('modalTanggal').textContent = `${data.mulai} s/d ${data.selesai}`;
      document.getElementById('modalPengajar').textContent = data.pengajar;

      modal.classList.remove('hidden');
      lucide.createIcons(); // re-render icons
    }
  </script>
</body>
</html>