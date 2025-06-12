<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$query = "SELECT * FROM sertifikat";
$result = mysqli_query($conn, $query);
?>

<h2>Daftar Sertifikat</h2>
<a href="add_sertifikat.php">+ Tambah Sertifikat</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama Sertifikat</th>
        <th>Template</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['ID_Sertifikat'] ?></td>
            <td><?= $row['Nama_Sertifikat'] ?></td>
            <td><?= $row['sertif_template'] ?? '—' ?></td>
            <td>
                <a href="edit_sertifikat.php?id=<?= $row['ID_Sertifikat'] ?>">Edit</a> |
                <a href="delete_sertifikat.php?id=<?= $row['ID_Sertifikat'] ?>" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
