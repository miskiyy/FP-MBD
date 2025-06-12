<?php
session_start();
require_once '../../config/database.php'; 

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}
$query = "SELECT * FROM user";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage User</title>
</head>
<body>
    <h2>Daftar User</h2>
    <a href="add_user.php">+ Tambah User Baru</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Kota</th>
            <th>Negara</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['ID'] ?></td>
            <td><?= $row['First_Name'] . ' ' . $row['Last_Name'] ?></td>
            <td><?= $row['Email'] ?></td>
            <td><?= $row['Kota'] ?></td>
            <td><?= $row['Negara'] ?></td>
            <td>
                <a href="edit_user.php?id=<?= $row['ID'] ?>">Edit</a> | 
                <a href="delete_user.php?id=<?= $row['ID'] ?>" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
