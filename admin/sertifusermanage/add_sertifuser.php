<?php
require_once '../../config/database.php';
require_once '../../models/sertifuser.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$success = "";
$error = "";
$sertifUserModel = new SertifUser($pdo);
$jenis = $_GET['jenis'] ?? 'event';

// Ambil data user-event/user-course yang eligible
if ($jenis === 'event') {
    // User-event yang belum dapat sertifikat event tsb
    $users = $pdo->query("
        SELECT 
            u.ID AS user_id,
            e.ID_event AS event_id,
            CONCAT(u.First_Name, ' ', COALESCE(u.Last_Name, '')) AS nama_user,
            e.Nama_Event,
            s.ID_Sertifikat,
            s.Nama_Sertifikat
        FROM user_event ue
        JOIN user u ON ue.User_ID = u.ID
        JOIN event e ON ue.Event_ID_Event = e.ID_event
        JOIN sertifikat s ON s.ID_Sertifikat = e.Sertifikat_ID_Sertifikat
        LEFT JOIN sertifuser su ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        WHERE su.User_ID IS NULL
        ORDER BY nama_user, e.Nama_Event
    ");
} else {
    // User-course yang sudah lulus dan belum dapat sertifikat course tsb
    $users = $pdo->query("
        SELECT 
            u.ID AS user_id,
            c.ID_courses AS course_id,
            CONCAT(u.First_Name, ' ', COALESCE(u.Last_Name, '')) AS nama_user,
            c.Nama_course,
            s.ID_Sertifikat,
            s.Nama_Sertifikat
        FROM user_course uc
        JOIN user u ON uc.User_ID = u.ID
        JOIN courses c ON uc.Courses_ID_Courses = c.ID_courses
        JOIN sertifikat s ON s.ID_Sertifikat = c.Sertifikat_ID_sertifikat
        LEFT JOIN sertifuser su ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        WHERE uc.Status_course = 'Lulus' AND su.User_ID IS NULL
        ORDER BY nama_user, c.Nama_course
    ");
}

// Proses insert hanya jika POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'] ?? null;
    $sertif_id = $_POST['sertif_id'] ?? null;

    if ($user_id && $sertif_id) {
        if (!$sertifUserModel->exists($user_id, $sertif_id)) {
            if ($sertifUserModel->add($user_id, $sertif_id)) {
                $success = "Sertifikat berhasil diberikan!";
            } else {
                $error = "Gagal insert.";
            }
        } else {
            $error = "User sudah punya sertifikat ini.";
        }
    } else {
        $error = "Data tidak lengkap.";
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
            <?php
            if ($users->rowCount() == 0) echo "<div class='text-red-500'>Tidak ada user yang eligible.</div>";
            ?>
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">User (yang ikut <?= $jenis ?> & eligible):</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih User --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['user_id'] ?>">
                                <?= htmlspecialchars($u['nama_user']) ?> (<?= $jenis === 'event' ? htmlspecialchars($u['Nama_Event']) : htmlspecialchars($u['Nama_course']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Sertifikat:</label>
                    <select name="sertif_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Sertifikat --</option>
                        <?php
                        // Sertifikat diambil dari hasil query user (karena 1 user hanya eligible 1 sertifikat per event/course)
                        $users->execute(); // reset pointer
                        $sertif_ids = [];
                        foreach ($users as $u) {
                            if (!in_array($u['ID_Sertifikat'], $sertif_ids)) {
                                $sertif_ids[] = $u['ID_Sertifikat'];
                                echo '<option value="' . $u['ID_Sertifikat'] . '">' . htmlspecialchars($u['Nama_Sertifikat']) . ' (' . htmlspecialchars($u['ID_Sertifikat']) . ')</option>';
                            }
                        }
                        ?>
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