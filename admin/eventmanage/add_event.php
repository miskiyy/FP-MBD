<?php
require_once '../../models/event.php';
require_once '../../models/sertifikat.php';
require_once '../../config/database.php';
require_once '../helpers/id_generator.php';

$eventModel = new Event($pdo);
$sertifikatModel = new Sertifikat($pdo);

$error = "";
$success = "";
$stmtKaryawan = $pdo->query("SELECT NIK, First_Name, Last_Name FROM karyawan");
$karyawans = $stmtKaryawan->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = generateIDUnique($pdo, 'event', 'ID_event', 'EV', 4);
    $sertif_id = generateIDUnique($pdo, 'sertifikat', 'ID_Sertifikat', 'SRF', 4);

    $nama_event = $_POST["Nama_Event"];
    $jenis_event = $_POST["Jenis_Event"];
    $deskripsi = trim($_POST["Deskripsi_Event"]) === "" ? null : $_POST["Deskripsi_Event"];
    $lokasi = $_POST["Lokasi_Acara"];
    $biaya = $_POST["Biaya_Pendaftaran"];
    $kuota = $_POST["Kuota_Pendaftaran"];
    $mulai = $_POST["tanggal_mulai_event"];
    $akhir = $_POST["tanggal_berakhir_event"];
    $nik = $_POST["Karyawan_NIK"];

    // 1. Insert Sertifikat dulu
    $defaultNama = "Event: " . $nama_event;
    $sertifInserted = $sertifikatModel->add($defaultNama, null, $sertif_id);

    if (!$sertifInserted) {
        $error = "Gagal membuat sertifikat.";
    } else {
        // 2. Insert Event
        $data = [
            $id,
            $nama_event,
            $jenis_event,
            $deskripsi,
            $lokasi,
            $biaya,
            $kuota,
            $mulai,
            $akhir,
            $sertif_id,
            $nik
        ];
        if ($eventModel->add($data)) {
            $success = "Event berhasil ditambahkan! Sertifikat ID: $sertif_id";
        } else {
            $error = "Gagal menambahkan event.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Event - CodingIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body { font-family: "Inter", sans-serif; }
        .select2-container .select2-selection--single {
            height: 42px !important;
            padding: 6px 12px;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            line-height: 28px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-3xl ml-0 md:ml-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-purple-700">Tambah Event</h2>
                <a href="manage_event.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Event</label>
                    <input type="text" name="Nama_Event" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Event</label>
                    <select name="Jenis_Event" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="Seminar">Seminar</option>
                        <option value="Workshop">Workshop</option>
                        <option value="Webinar">Webinar</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Deskripsi Event</label>
                    <textarea name="Deskripsi_Event" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Lokasi Acara</label>
                    <input type="text" name="Lokasi_Acara" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Biaya Pendaftaran</label>
                        <input type="number" name="Biaya_Pendaftaran" step="0.01" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kuota Pendaftaran</label>
                        <input type="number" name="Kuota_Pendaftaran" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai_event" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Berakhir</label>
                        <input type="date" name="tanggal_berakhir_event" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Karyawan (NIK)</label>
                    <select name="Karyawan_NIK" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Karyawan --</option>
                    <?php foreach ($karyawans as $row): ?>
                        <option value="<?= $row['NIK'] ?>">
                            <?= htmlspecialchars($row['NIK']) ?> - <?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Tambah Event</button>
                </div>
            </form>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="Karyawan_NIK"]').select2();
        });
    </script>
</body>
</html>