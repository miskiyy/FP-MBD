<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$success = "";
$error = "";

$jenis = $_GET['jenis'] ?? 'event';

// === Data user yang ikut event/course dan belum punya sertifikat ===
if ($jenis === 'event') {
    $users = $conn->query("
        SELECT u.ID, CONCAT(u.First_Name, ' ', u.Last_Name) AS Nama
        FROM user u
        JOIN user_event ue ON ue.User_ID = u.ID
        JOIN event e ON e.ID_event = ue.Event_ID_Event
        JOIN sertifikat s ON s.Nama_Sertifikat = CONCAT('Event: ', e.Nama_Event)
        LEFT JOIN sertifuser su ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        WHERE su.User_ID IS NULL
        GROUP BY u.ID
    ");

    $sertifikat = $conn->query("
        SELECT s.ID_Sertifikat, s.Nama_Sertifikat
        FROM sertifikat s
        WHERE s.Nama_Sertifikat LIKE 'Event:%'
        AND s.ID_Sertifikat NOT IN (
            SELECT Sertifikat_ID_Sertifikat FROM sertifuser
        )
    ");
} else {
    $users = $conn->query("
        SELECT u.ID, CONCAT(u.First_Name, ' ', u.Last_Name) AS Nama
        FROM user u
        JOIN user_course uc ON uc.User_ID = u.ID
        JOIN courses c ON c.ID_courses = uc.Courses_ID_Courses
        JOIN sertifikat s ON s.Nama_Sertifikat = CONCAT('Course: ', c.Nama_course)
        LEFT JOIN sertifuser su ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        WHERE su.User_ID IS NULL
        GROUP BY u.ID
    ");

    $sertifikat = $conn->query("
        SELECT s.ID_Sertifikat, s.Nama_Sertifikat
        FROM sertifikat s
        WHERE s.Nama_Sertifikat LIKE 'Course:%'
        AND s.ID_Sertifikat NOT IN (
            SELECT Sertifikat_ID_Sertifikat FROM sertifuser
        )
    ");
}

// === Insert Sertifikat ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST["user_id"];
    $sertif_id = $_POST["sertif_id"];

    if ($user_id && $sertif_id) {
        // Insert langsung karena data sudah disaring sebelumnya
        $stmt = $conn->prepare("INSERT INTO sertifuser (User_ID, Sertifikat_ID_Sertifikat) VALUES (?, ?)");
        $stmt->bind_param("ss", $user_id, $sertif_id);

        if ($stmt->execute()) {
            $success = "Sertifikat berhasil diberikan!";
        } else {
            $error = "Gagal insert: " . $stmt->error;
        }
    } else {
        $error = "Data tidak lengkap.";
    }
}
?>

<h2>Tambah Sertifikat User (<?= ucfirst($jenis) ?>)</h2>

<form method="get" style="margin-bottom: 10px;">
    <label>Jenis:</label>
    <select name="jenis" onchange="this.form.submit()">
        <option value="event" <?= $jenis === 'event' ? 'selected' : '' ?>>Event</option>
        <option value="course" <?= $jenis === 'course' ? 'selected' : '' ?>>Course</option>
    </select>
</form>

<?php if ($success): ?><p style="color:green"><?= $success ?></p><?php endif; ?>
<?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>

<form method="post">
    <label>User (yang ikut <?= $jenis ?> & belum punya sertif):</label><br>
    <select name="user_id" required>
        <option value="">-- Pilih User --</option>
        <?php while ($u = $users->fetch_assoc()): ?>
            <option value="<?= $u['ID'] ?>"><?= $u['Nama'] ?> (<?= $u['ID'] ?>)</option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Sertifikat Tersedia:</label><br>
    <select name="sertif_id" required>
        <option value="">-- Pilih Sertifikat --</option>
        <?php while ($s = $sertifikat->fetch_assoc()): ?>
            <option value="<?= $s['ID_Sertifikat'] ?>"><?= $s['Nama_Sertifikat'] ?> (<?= $s['ID_Sertifikat'] ?>)</option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Tambah Sertifikat</button>
</form>

<a href="manage_sertifuser.php">← Kembali</a>