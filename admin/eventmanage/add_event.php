<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

function generateRandomID($prefix, $length = 4) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $prefix . $randomString;
}

$error = "";
$success = "";
$queryKaryawan = "SELECT NIK, First_Name, Last_Name FROM karyawan";
$resultKaryawan = mysqli_query($conn, $queryKaryawan);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = generateRandomID("EV");
    $nama = $_POST["Nama_Event"];
    $jenis = $_POST["Jenis_Event"];
    $deskripsi = trim($_POST["Deskripsi_Event"]) === "" ? null : $_POST["Deskripsi_Event"];
    $lokasi = $_POST["Lokasi_Acara"];
    $biaya = $_POST["Biaya_Pendaftaran"];
    $kuota = $_POST["Kuota_Pendaftaran"];
    $mulai = $_POST["tanggal_mulai_event"];
    $akhir = $_POST["tanggal_berakhir_event"];
    $sertifikat = $_POST["Sertifikat_ID_Sertifikat"];
    $nik = $_POST["Karyawan_NIK"];

    $query = "INSERT INTO event VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssdissss", $id, $nama, $jenis, $deskripsi, $lokasi, $biaya, $kuota, $mulai, $akhir, $sertifikat, $nik);

    if ($stmt->execute()) {
        $success = "Event berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan event: " . $stmt->error;
    }

    $resultKaryawan = mysqli_query($conn, $queryKaryawan);
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
                    <label class="block text-sm font-medium mb-1">ID Sertifikat (default: "notassign")</label>
                    <input type="text" name="Sertifikat_ID_Sertifikat" value="notassign" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Karyawan (NIK)</label>
                    <select name="Karyawan_NIK" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Karyawan --</option>
                        <?php
                        mysqli_data_seek($resultKaryawan, 0);
                        while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
                            <option value="<?= $row['NIK'] ?>">
                                <?= htmlspecialchars($row['NIK']) ?> - <?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) ?>
                            </option>
                        <?php endwhile; ?>
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