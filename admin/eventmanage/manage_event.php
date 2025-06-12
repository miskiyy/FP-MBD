<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$query = "SELECT * FROM event";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Event</title>
</head>
<body>
    <h2>Manajemen Event</h2>
    <a href="add_event.php">Tambah Event</a><br><br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Deskripsi</th>
            <th>Lokasi</th>
            <th>Biaya</th>
            <th>Kuota</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Berakhir</th>
            <th>ID Sertifikat</th>
            <th>NIK Karyawan</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['ID_event'] ?></td>
            <td><?= $row['Nama_Event'] ?></td>
            <td><?= $row['Jenis_Event'] ?></td>
            <td><?= $row['Deskripsi_Event'] ?></td>
            <td><?= $row['Lokasi_Acara'] ?></td>
            <td><?= $row['Biaya_Pendaftaran'] ?></td>
            <td><?= $row['Kuota_Pendaftaran'] ?></td>
            <td><?= $row['tanggal_mulai_event'] ?></td>
            <td><?= $row['tanggal_berakhir_event'] ?></td>
            <td><?= $row['Sertifikat_ID_Sertifikat'] ?></td>
            <td><?= $row['Karyawan_NIK'] ?></td>
            <td>
                <a href="edit_event.php?id=<?= $row['ID_event'] ?>">Edit</a> |
                <a href="delete_event.php?id=<?= $row['ID_event'] ?>" onclick="return confirm('Yakin mau hapus event ini?');">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>