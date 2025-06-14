<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$error = "";
$success = "";

$queryKaryawan = "SELECT NIK, First_Name, Last_Name FROM karyawan";
$resultKaryawan = mysqli_query($conn, $queryKaryawan);

$id = $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST["ID_event"] : $_GET["id"];

// Ambil data event
$query = "SELECT * FROM event WHERE ID_event = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    $error = "Event tidak ditemukan.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    $queryUpdate = "UPDATE event SET Nama_Event=?, Jenis_Event=?, Deskripsi_Event=?, Lokasi_Acara=?, Biaya_Pendaftaran=?, Kuota_Pendaftaran=?, tanggal_mulai_event=?, tanggal_berakhir_event=?, Sertifikat_ID_Sertifikat=?, Karyawan_NIK=? WHERE ID_Event=?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("sssssdissss", $nama, $jenis, $deskripsi, $lokasi, $biaya, $kuota, $mulai, $akhir, $sertifikat, $nik, $id);

    if ($stmtUpdate->execute()) {
        $success = "Event berhasil diperbarui!";
        // Refresh data
        $event["Nama_Event"] = $nama;
        $event["Jenis_Event"] = $jenis;
        $event["Deskripsi_Event"] = $deskripsi;
        $event["Lokasi_Acara"] = $lokasi;
        $event["Biaya_Pendaftaran"] = $biaya;
        $event["Kuota_Pendaftaran"] = $kuota;
        $event["tanggal_mulai_event"] = $mulai;
        $event["tanggal_berakhir_event"] = $akhir;
        $event["Sertifikat_ID_Sertifikat"] = $sertifikat;
        $event["Karyawan_NIK"] = $nik;
    } else {
        $error = "Gagal update: " . $stmtUpdate->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Event - CodingIn</title>
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
                <h2 class="text-2xl font-bold text-purple-700">Edit Event</h2>
                <a href="manage_event.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($event): ?>
            <form method="post" class="space-y-4">
                <input type="hidden" name="ID_event" value="<?= htmlspecialchars($event['ID_event']) ?>">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Event</label>
                    <input type="text" name="Nama_Event" value="<?= htmlspecialchars($event['Nama_Event']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Event</label>
                    <select name="Jenis_Event" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="Seminar" <?= $event['Jenis_Event'] == 'Seminar' ? 'selected' : '' ?>>Seminar</option>
                        <option value="Workshop" <?= $event['Jenis_Event'] == 'Workshop' ? 'selected' : '' ?>>Workshop</option>
                        <option value="Webinar" <?= $event['Jenis_Event'] == 'Webinar' ? 'selected' : '' ?>>Webinar</option>
                        <option value="Lainnya" <?= $event['Jenis_Event'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Deskripsi Event</label>
                    <textarea name="Deskripsi_Event" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200"><?= htmlspecialchars($event['Deskripsi_Event']) ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Lokasi Acara</label>
                    <input type="text" name="Lokasi_Acara" value="<?= htmlspecialchars($event['Lokasi_Acara']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Biaya Pendaftaran</label>
                        <input type="number" name="Biaya_Pendaftaran" step="0.01" value="<?= htmlspecialchars($event['Biaya_Pendaftaran']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kuota Pendaftaran</label>
                        <input type="number" name="Kuota_Pendaftaran" value="<?= htmlspecialchars($event['Kuota_Pendaftaran']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai_event" value="<?= htmlspecialchars($event['tanggal_mulai_event']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Berakhir</label>
                        <input type="date" name="tanggal_berakhir_event" value="<?= htmlspecialchars($event['tanggal_berakhir_event']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">ID Sertifikat</label>
                    <input type="text" name="Sertifikat_ID_Sertifikat" value="<?= htmlspecialchars($event['Sertifikat_ID_Sertifikat']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-500" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Karyawan (NIK)</label>
                    <select name="Karyawan_NIK" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Karyawan --</option>
                        <?php
                        mysqli_data_seek($resultKaryawan, 0);
                        while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
                            <option value="<?= $row['NIK'] ?>" <?= $event['Karyawan_NIK'] == $row['NIK'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['NIK']) ?> - <?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Simpan Perubahan</button>
                </div>
            </form>
            <?php endif; ?>
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