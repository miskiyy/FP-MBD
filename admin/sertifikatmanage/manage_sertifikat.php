<?php
require_once '../../config/database.php';
require_once '../../models/sertifikat.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$sertifikatModel = new Sertifikat($pdo);
$rows = $sertifikatModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Sertifikat - CodingIn</title>
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
    <div class="bg-white rounded-xl shadow p-8 w-full max-w-4xl ml-0 md:ml-12">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold text-purple-700">📄 Daftar Sertifikat</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg text-xs sm:text-sm">
          <thead class="bg-purple-50">
            <tr>
              <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">ID</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">Nama Sertifikat</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">Template</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700 border-b">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php foreach ($rows as $row): ?>
              <tr class="hover:bg-purple-50">
                <td class="py-3 px-4"><?= htmlspecialchars($row['ID_Sertifikat']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['Nama_Sertifikat']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['sertif_template'] ?? '—') ?></td>
                <td class="py-3 px-4">
                  <div class="flex justify-center gap-x-2">
                    <a href="edit_sertifikat.php?id=<?= urlencode($row['ID_Sertifikat']) ?>"
                      class="inline-flex items-center justify-center bg-yellow-400 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-yellow-500 transition text-xs shadow-sm">
                      Edit
                    </a>
                    <a href="delete_sertifikat.php?id=<?= urlencode($row['ID_Sertifikat']) ?>"
                      class="inline-flex items-center justify-center bg-red-500 text-white px-4 py-1.5 rounded-lg font-medium hover:bg-red-600 transition text-xs shadow-sm"
                      onclick="return confirm('Yakin mau hapus?')">
                      Hapus
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
              <tr>
                <td colspan="4" class="text-center text-gray-400 py-6">Belum ada sertifikat yang tersedia.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>
</html>