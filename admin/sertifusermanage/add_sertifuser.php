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
    $user_sertif_arr = $_POST['user_sertif'] ?? [];
    $berhasil = 0; $gagal = 0;

    foreach ($user_sertif_arr as $val) {
        if (strpos($val, '|') !== false) {
            list($user_id, $sertif_id) = explode('|', $val, 2);
            if (!$sertifUserModel->exists($user_id, $sertif_id)) {
                if ($sertifUserModel->add($user_id, $sertif_id)) {
                    $berhasil++;
                } else {
                    $gagal++;
                }
            } else {
                $gagal++;
            }
        }
    }
    if ($berhasil > 0) $success = "$berhasil sertifikat berhasil diberikan!";
    if ($gagal > 0) $error = "$gagal gagal (sudah ada atau error).";
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
                <label class="block text-sm font-medium mb-1">Pilih User yang akan diberikan sertifikat:</label>
                <div class="max-h-64 overflow-y-auto border rounded px-3 py-2 bg-gray-50">
                    <?php foreach ($users as $u): ?>
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="user_sertif[]" value="<?= $u['user_id'] ?>|<?= $u['ID_Sertifikat'] ?>" class="mr-2">
                            <?= htmlspecialchars($u['nama_user']) ?> (<?= $jenis === 'event' ? htmlspecialchars($u['Nama_Event']) : htmlspecialchars($u['Nama_course']) ?>)
                        </label>
                    <?php endforeach; ?>
                    <?php if ($users->rowCount() == 0): ?>
                        <span class="text-gray-400">Tidak ada user yang eligible.</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Tambah Sertifikat</button>
            </div>
        </form>
        </div>
    </main>
</body>
</html>