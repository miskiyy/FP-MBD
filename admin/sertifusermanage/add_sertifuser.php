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
        SELECT u.ID, CONCAT(COALESCE(u.First_Name, ''), ' ', COALESCE(u.Last_Name, '')) AS Nama
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
    // Refresh data
    if ($jenis === 'event') {
        $users = $conn->query("
            SELECT u.ID, CONCAT(COALESCE(u.First_Name, ''), ' ', COALESCE(u.Last_Name, '')) AS Nama
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
            SELECT u.ID, CONCAT(COALESCE(u.First_Name, ''), ' ', COALESCE(u.Last_Name, '')) AS Nama
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
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Sertifikat User - CodingIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-lg ml-0 md:ml-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-purple-700">Tambah Sertifikat User (<?= ucfirst($jenis) ?>)</h2>
                <a href="manage_sertifuser.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <form method="get" class="mb-6">
                <label class="block text-sm font-medium mb-1">Jenis:</label>
                <select name="jenis" onchange="this.form.submit()" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200 max-w-xs">
                    <option value="event" <?= $jenis === 'event' ? 'selected' : '' ?>>Event</option>
                    <option value="course" <?= $jenis === 'course' ? 'selected' : '' ?>>Course</option>
                </select>
            </form>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">User (yang ikut <?= $jenis ?> & belum punya sertif):</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih User --</option>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <option value="<?= $u['ID'] ?>"><?= htmlspecialchars($u['Nama']) ?> (<?= htmlspecialchars($u['ID']) ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Sertifikat Tersedia:</label>
                    <select name="sertif_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Sertifikat --</option>
                        <?php while ($s = $sertifikat->fetch_assoc()): ?>
                            <option value="<?= $s['ID_Sertifikat'] ?>"><?= htmlspecialchars($s['Nama_Sertifikat']) ?> (<?= htmlspecialchars($s['ID_Sertifikat']) ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Tambah Sertifikat</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>