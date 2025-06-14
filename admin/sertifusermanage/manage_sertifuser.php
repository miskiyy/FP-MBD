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
            CONCAT(COALESCE(u.First_Name, ''), ' ', COALESCE(u.Last_Name, '')) AS Nama,
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
            CONCAT(COALESCE(u.First_Name, ''), ' ', COALESCE(u.Last_Name, '')) AS Nama,
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

$stmt = $pdo->query($query);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Sertifikat User - CodingIn</title>
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
    <div class="bg-white rounded-xl shadow p-8 w-full max-w-5xl ml-0 md:ml-12">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold text-purple-700">🎓 Manajemen Sertifikat User (<?= ucfirst($tipe) ?> Based)</h2>
        <div class="flex gap-2">
          <a href="?tipe=event" class="px-3 py-1 rounded-full text-sm font-semibold <?= $tipe == 'event' ? 'bg-purple-700 text-white' : 'bg-purple-100 text-purple-700 hover:bg-purple-200' ?>">🔖 Event</a>
          <a href="?tipe=course" class="px-3 py-1 rounded-full text-sm font-semibold <?= $tipe == 'course' ? 'bg-purple-700 text-white' : 'bg-purple-100 text-purple-700 hover:bg-purple-200' ?>">📚 Course</a>
        </div>
      </div>
      <div class="mb-4">
        <a href="add_sertifuser.php" class="inline-block bg-purple-700 text-white px-5 py-2 rounded-full font-semibold hover:bg-purple-800 transition text-sm shadow">
          + Assign Sertifikat
        </a>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg text-xs sm:text-sm">
          <thead class="bg-purple-50">
            <tr>
              <th class="py-3 px-4 font-semibold text-gray-700 border-b">No</th>
              <th class="py-3 px-4 font-semibold text-gray-700 border-b">User ID</th>
              <th class="py-3 px-4 font-semibold text-gray-700 border-b">Nama</th>
              <th class="py-3 px-4 font-semibold text-gray-700 border-b">Sertifikat</th>
              <th class="py-3 px-4 font-semibold text-gray-700 border-b">ID Sertifikat</th>
              <th class="py-3 px-4 font-semibold text-gray-700 border-b">Status</th>
              <th class="py-3 px-4 font-semibold text-gray-700 border-b">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php $no = 1; foreach ($rows as $row): ?>
              <?php $sudah_terbit = !is_null($row['tanggal_diberikan']); ?>
              <tr class="hover:bg-purple-50">
                <td class="py-3 px-4"><?= $no++ ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['User_ID']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['Nama']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['Nama_Sertifikat']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['ID_Sertifikat']) ?></td>
                <td class="py-3 px-4">
                  <?php if ($sudah_terbit): ?>
                    <span class="text-green-600 font-semibold">Sudah Diberikan</span><br>
                    <small><?= htmlspecialchars($row['tanggal_diberikan']) ?></small>
                  <?php else: ?>
                    <span class="text-red-600 font-semibold">Belum</span>
                  <?php endif; ?>
                </td>
                <td class="py-3 px-4">
                  <div class="flex gap-2">
                    <?php if (!$sudah_terbit): ?>
                      <form action="give_sertifuser.php" method="post" class="inline">
                        <input type="hidden" name="user_id" value="<?= $row['User_ID'] ?>">
                        <input type="hidden" name="sertif_id" value="<?= $row['ID_Sertifikat'] ?>">
                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-green-700 transition">Terbitkan</button>
                      </form>
                    <?php else: ?>
                      <form action="cancel_sertifuser.php" method="post" class="inline" onsubmit="return confirm('Yakin ingin mencabut sertifikat ini?');">
                        <input type="hidden" name="user_id" value="<?= $row['User_ID'] ?>">
                        <input type="hidden" name="sertif_id" value="<?= $row['ID_Sertifikat'] ?>">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-red-600 transition">Cabut</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
              <tr>
                <td colspan="7" class="text-center text-gray-400 py-6">Belum ada data sertifikat user.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>
</html>