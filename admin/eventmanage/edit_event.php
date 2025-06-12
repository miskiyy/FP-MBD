<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_event.php");
    exit();
}

$id = $_GET["id"];
$error = "";
$success = "";

$query = "SELECT * FROM event WHERE ID_event = ?";
$queryKaryawan = "SELECT NIK, First_Name, Last_Name FROM karyawan";
$resultKaryawan = mysqli_query($conn, $queryKaryawan);
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
    $deskripsi = $_POST["Deskripsi_Event"];
    $lokasi = $_POST["Lokasi_Acara"];
    $biaya = $_POST["Biaya_Pendaftaran"];
    $kuota = $_POST["Kuota_Pendaftaran"];
    $mulai = $_POST["tanggal_mulai_event"];
    $akhir = $_POST["tanggal_berakhir_event"];
    $sertifikat = $_POST["Sertifikat_ID_Sertifikat"];
    $nik = $_POST["Karyawan_NIK"];

    $queryUpdate = "UPDATE event SET Nama_Event=?, Jenis_Event=?, Deskripsi_Event=?, Lokasi_Acara=?, Biaya_Pendaftaran=?, Kuota_Pendaftaran=?, tanggal_mulai_event=?, tanggal_berakhir_event=?, Sertifikat_ID_Sertifikat=?, Karyawan_NIK=? WHERE ID_event=?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("sssssisssss", $nama, $jenis, $deskripsi, $lokasi, $biaya, $kuota, $mulai, $akhir, $sertifikat, $nik, $id);

    if ($stmtUpdate->execute()) {
        $success = "Event berhasil diperbarui!";
    } else {
        $error = "Gagal update: " . $stmtUpdate->error;
    }
    
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
    <h2>Edit Event</h2>
    <form method="post">
    <input type="hidden" name="ID_event" value="<?= $event['ID_event'] ?>">

    <label>Nama Event:</label><br>
    <input type="text" name="Nama_Event" value="<?= $event['Nama_Event'] ?>" required><br>

    <label>Jenis Event:</label><br>
    <select name="Jenis_Event" required>
        <option value="Seminar" <?= isset($event) && $event['Jenis_Event'] === 'Seminar' ? 'selected' : '' ?>>Seminar</option>
        <option value="Workshop" <?= isset($event) && $event['Jenis_Event'] === 'Workshop' ? 'selected' : '' ?>>Workshop</option>
        <option value="Webinar" <?= isset($event) && $event['Jenis_Event'] === 'Webinar' ? 'selected' : '' ?>>Webinar</option>
        <option value="Lainnya" <?= isset($event) && $event['Jenis_Event'] === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
    </select><br>

    <label>Deskripsi Event:</label><br>
    <textarea name="Deskripsi_Event"><?= $event['Deskripsi_Event'] ?></textarea><br>

    <label>Lokasi Acara:</label><br>
    <input type="text" name="Lokasi_Acara" value="<?= $event['Lokasi_Acara'] ?>" required><br>

    <label>Biaya Pendaftaran:</label><br>
    <input type="number" name="Biaya_Pendaftaran" step="0.01" value="<?= $event['Biaya_Pendaftaran'] ?>" required><br>

    <label>Kuota Pendaftaran:</label><br>
    <input type="number" name="Kuota_Pendaftaran" value="<?= $event['Kuota_Pendaftaran'] ?>" required><br>

    <label>Tanggal Mulai:</label><br>
    <input type="date" name="tanggal_mulai_event" value="<?= $event['tanggal_mulai_event'] ?>" required><br>

    <label>Tanggal Berakhir:</label><br>
    <input type="date" name="tanggal_berakhir_event" value="<?= $event['tanggal_berakhir_event'] ?>" required><br>

    <label>ID Sertifikat:</label><br>
    <input type="text" name="Sertifikat_ID_Sertifikat" value="<?= $event['Sertifikat_ID_Sertifikat'] ?>" required><br>

    <label>Karyawan (NIK):</label><br>
    <select name="Karyawan_NIK" required>
        <option value="">-- Pilih Karyawan --</option>
        <?php while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
            <option value="<?= $row['NIK'] ?>" <?= $row['NIK'] == $event['Karyawan_NIK'] ? 'selected' : '' ?>>
                <?= $row['NIK'] ?> - <?= $row['First_Name'] . ' ' . $row['Last_Name'] ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <br><button type="submit">Simpan Perubahan</button>
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