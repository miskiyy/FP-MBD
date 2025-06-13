<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: login.php");
    exit();
}
$admin = $_SESSION["NIK"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include __DIR__ . '/assets/template/admin.php'; ?>
    <main class="flex-1 p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Halo admin 👋</h2>
        <p class="text-gray-600 mb-8">Selamat datang, <span class="font-semibold text-purple-700"><?= htmlspecialchars($admin) ?></span>!</p>
        <!-- Konten dashboard di sini -->
    </main>
</body>
</html>