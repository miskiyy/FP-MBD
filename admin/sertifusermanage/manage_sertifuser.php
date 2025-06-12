<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$tipe = $_GET["tipe"] ?? "event";

// Query berdasarkan tipe
if ($tipe == "course") {
    $query = "
        SELECT 
            u.ID AS User_ID,
            CONCAT(u.First_Name, ' ', u.Last_Name) AS Nama,
            c.Nama_course,
            s.ID_Sertifikat,
            s.Nama_Sertifikat,
            su.tanggal_diberikan
        FROM user u
        JOIN user_course uc ON uc.User_ID = u.ID
        JOIN courses c ON c.ID_courses = uc.Courses_ID_Courses
        JOIN sertifikat s ON s.ID_Sertifikat = c.Sertifikat_ID_sertifikat
        LEFT JOIN sertifuser su 
            ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        ORDER BY u.ID
    ";
} else {
    $query = "
        SELECT 
            u.ID AS User_ID,
            CONCAT(u.First_Name, ' ', u.Last_Name) AS Nama,
            e.Nama_Event,
            s.ID_Sertifikat,
            s.Nama_Sertifikat,
            su.tanggal_diberikan
        FROM user u
        JOIN user_event ue ON ue.User_ID = u.ID
        JOIN event e ON e.ID_event = ue.Event_ID_Event
        JOIN sertifikat s ON s.ID_Sertifikat = e.Sertifikat_ID_Sertifikat
        LEFT JOIN sertifuser su 
            ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        ORDER BY u.ID
    ";
}

$result = $conn->query($query);

if (!$result) {
    die("Query error: " . $conn->error);
}

?>

<h2>Manajemen Sertifikat User (<?= ucfirst($tipe) ?> Based)</h2>
<a href="?tipe=event">🔖 Berdasarkan Event</a> | <a href="?tipe=course">📚 Berdasarkan Course</a>
<br><br>

<!-- add sertif ke add_sertifuser.php -->
<a href="add_sertifuser.php">Assign Sertifikat</a>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>User ID</th>
        <th>Nama</th>
        <th>Sertifikat</th>
        <th>ID Sertifikat</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while ($row = $result->fetch_assoc()):
        $sudah_terbit = !is_null($row['tanggal_diberikan']);
    ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['User_ID']) ?></td>
        <td><?= htmlspecialchars($row['Nama']) ?></td>
        <td><?= htmlspecialchars($row['Nama_Sertifikat']) ?></td>
        <td><?= htmlspecialchars($row['ID_Sertifikat']) ?></td>

        <td>
            <?= $sudah_terbit 
                ? "<span style='color: green;'>Sudah Diberikan</span><br><small>{$row['tanggal_diberikan']}</small>"
                : "<span style='color: red;'>Belum</span>" ?>
        </td>
        <td>
            <?php if (!$sudah_terbit): ?>
                <form action="give_sertifuser.php" method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $row['User_ID'] ?>">
                    <input type="hidden" name="sertif_id" value="<?= $row['ID_Sertifikat'] ?>">
                    <button type="submit">Terbitkan</button>
                </form>
            <?php else: ?>
                <form action="cancel_sertifuser.php" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin mencabut sertifikat ini?');">
                    <input type="hidden" name="user_id" value="<?= $row['User_ID'] ?>">
                    <input type="hidden" name="sertif_id" value="<?= $row['ID_Sertifikat'] ?>">
                    <button type="submit" style="background-color: #f66;">Cabut</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
