<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$query = "SELECT * FROM challenge";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Challenge</title>
</head>
<body>
    <h2>Daftar Challenge</h2>
    <a href="add_challenge.php">Tambah Challenge</a><br><br>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th><th>Nama</th><th>Deskripsi</th><th>Tanggal Mulai</th><th>Tanggal Berakhir</th><th>Kuota</th><th>Hadiah</th><th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row['id_challenge'] ?></td>
                <td><?= $row['nama_challenge'] ?></td>
                <td><?= $row['deskripsi'] ?></td>
                <td><?= $row['tanggal_mulai'] ?></td>
                <td><?= $row['tanggal_berakhir'] ?></td>
                <td><?= $row['kuota_pemenang'] ?></td>
                <td><?= $row['hadiah'] ?></td>
                <td>
                    <a href="edit_challenge.php?id=<?= $row['id_challenge'] ?>">Edit</a> |
                    <a href="delete_challenge.php?id=<?= $row['id_challenge'] ?>" onclick="return confirm('Yakin hapus?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>