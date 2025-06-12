<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$query = "SELECT * FROM courses";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Course</title>
</head>
<body>
    <h2>Daftar Course</h2>
    <a href="add_course.php">+ Tambah Course Baru</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Rating</th>
            <th>Tingkat Kesulitan</th>
            <th>ID Sertifikat</th>
            <th>NIK Karyawan</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['ID_courses'] ?></td>
            <td><?= $row['Nama_course'] ?></td>
            <td><?= $row['Rating_course'] ?></td>
            <td><?= $row['Tingkat_kesulitan'] ?></td>
            <td><?= $row['Sertifikat_ID_sertifikat'] ?></td>
            <td><?= $row['Karyawan_NIK'] ?></td>
            <td>
                <a href="edit_course.php?id=<?= $row['ID_courses'] ?>">Edit</a> | 
                <a href="delete_course.php?id=<?= $row['ID_courses'] ?>" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
