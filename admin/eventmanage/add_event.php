<?php
session_start();
require_once '../../config/database.php';
require_once '../helpers/id_generator.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$error = "";
$success = "";

$queryKaryawan = "SELECT NIK, First_Name, Last_Name FROM karyawan";
$resultKaryawan = mysqli_query($conn, $queryKaryawan);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = generateIDUnique($conn, 'event', 'ID_event', 'EV', 4);         // EVxxxx
    $sertif_id = generateIDUnique($conn, 'sertifikat', 'ID_Sertifikat', 'SRF', 4); // SRFxxxx

    $nama = $_POST["Nama_Event"];
    $jenis = $_POST["Jenis_Event"];
    $deskripsi = trim($_POST["Deskripsi_Event"]);
    $lokasi = $_POST["Lokasi_Acara"];
    $biaya = $_POST["Biaya_Pendaftaran"];
    $kuota = $_POST["Kuota_Pendaftaran"];
    $mulai = $_POST["tanggal_mulai_event"];
    $akhir = $_POST["tanggal_berakhir_event"];
    $nik = $_POST["Karyawan_NIK"];

    if ($deskripsi === "") $deskripsi = null;

    if ($nama && $jenis && $lokasi && $biaya && $kuota && $mulai && $akhir && $nik) {
        // 1. Insert ke sertifikat dulu
        $sertif_nama = "Event: " . $nama;
        $stmtSertif = $conn->prepare("INSERT INTO sertifikat (ID_Sertifikat, Nama_Sertifikat) VALUES (?, ?)");
        $stmtSertif->bind_param("ss", $sertif_id, $sertif_nama);
        $insertSertif = $stmtSertif->execute();

        if (!$insertSertif) {
            $error = "Gagal membuat sertifikat: " . $stmtSertif->error;
        } else {
            // 2. Insert event
            $query = "INSERT INTO event 
                (ID_event, Nama_Event, Jenis_Event, Deskripsi_Event, Lokasi_Acara, Biaya_Pendaftaran, Kuota_Pendaftaran,
                 tanggal_mulai_event, tanggal_berakhir_event, Sertifikat_ID_Sertifikat, Karyawan_NIK)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssdissss", $id, $nama, $jenis, $deskripsi, $lokasi, $biaya, $kuota, $mulai, $akhir, $sertif_id, $nik);

            if ($stmt->execute()) {
                $success = "Event berhasil ditambahkan! Sertifikat ID: $sertif_id";
            } else {
                $error = "Gagal menambahkan event: " . $stmt->error;
            }
        }
    } else {
        $error = "Pastikan semua field terisi dengan benar.";
    }

    // refresh ulang data karyawan biar form tetap bisa kebaca
    $resultKaryawan = mysqli_query($conn, $queryKaryawan);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <h2>Tambah Event</h2>
    <form method="post">
    <label>Nama Event:</label><br>
    <input type="text" name="Nama_Event" required><br>

    <label>Jenis Event:</label><br>
    <select name="Jenis_Event" required>
        <option value="Seminar" <?= isset($event) && $event['Jenis_Event'] === 'Seminar' ? 'selected' : '' ?>>Seminar</option>
        <option value="Workshop" <?= isset($event) && $event['Jenis_Event'] === 'Workshop' ? 'selected' : '' ?>>Workshop</option>
        <option value="Webinar" <?= isset($event) && $event['Jenis_Event'] === 'Webinar' ? 'selected' : '' ?>>Webinar</option>
        <option value="Lainnya" <?= isset($event) && $event['Jenis_Event'] === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
    </select><br>

    <label>Deskripsi Event:</label><br>
    <textarea name="Deskripsi_Event"></textarea><br>

    <label>Lokasi Acara:</label><br>
    <input type="text" name="Lokasi_Acara" required><br>

    <label>Biaya Pendaftaran:</label><br>
    <input type="number" name="Biaya_Pendaftaran" step="0.01" required><br>

    <label>Kuota Pendaftaran:</label><br>
    <input type="number" name="Kuota_Pendaftaran" required><br>

    <label>Tanggal Mulai:</label><br>
    <input type="date" name="tanggal_mulai_event" required><br>

    <label>Tanggal Berakhir:</label><br>
    <input type="date" name="tanggal_berakhir_event" required><br>

    <label>Karyawan (NIK):</label><br>
    <select name="Karyawan_NIK" required>
        <option value="">-- Pilih Karyawan --</option>
        <?php while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
            <option value="<?= $row['NIK'] ?>">
                <?= $row['NIK'] ?> - <?= $row['First_Name'] . ' ' . $row['Last_Name'] ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <br><button type="submit">Tambah Event</button>
    </form>
    <a href="manage_event.php">Kembali</a>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="Karyawan_NIK"]').select2();
        });
    </script>
</body>
</html>
