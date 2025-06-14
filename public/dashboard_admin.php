<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "karyawan") {
    header("Location: login.php");
    exit();
}
require_once '../config/database.php';

$firstName = '';
$lastName = '';
if (isset($_SESSION["NIK"])) {
    $stmt = $pdo->prepare("SELECT First_Name, Last_Name FROM karyawan WHERE NIK = ?");
    $stmt->execute([$_SESSION["NIK"]]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        $firstName = $data['First_Name'];
        $lastName = $data['Last_Name'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - CodingIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Admin</h2>
        <p class="text-gray-600 mb-4">
            Halo, <span class="font-semibold text-purple-700">
                <?= htmlspecialchars(trim($firstName . ' ' . $lastName)) ?>
            </span>! Selamat datang di dashboard admin CodingIn.
        </p>
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Selamat datang di Dashboard Admin!</h3>
            <p class="text-gray-600">Di sini kamu bisa mengelola event, peserta, dan data lainnya. Semangat dalam mengelola CodingIn!</p>
    </main>
</body>
</html>