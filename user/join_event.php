<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$event_id = $_POST["event_id"] ?? $_GET["id"] ?? null;

if (!$event_id) {
    die("Event tidak ditemukan.");
}

// Ambil detail event
$stmt = $pdo->prepare("SELECT * FROM event WHERE ID_event = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    die("Event tidak ditemukan.");
}

// Cek status sukses dari redirect GET
$pesan = "";
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    $pesan = "Pendaftaran berhasil!";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_GET['status'])) {
    $metode = $_POST["metode_pembayaran"] ?? "QRIS";

    // Cek apakah user sudah mendaftar
    $cek = $pdo->prepare("SELECT 1 FROM user_event WHERE User_ID = ? AND Event_ID_Event = ?");
    $cek->execute([$user_id, $event_id]);

    if (!$cek->fetch()) {
        // Daftarkan user
        $insert = $pdo->prepare("INSERT INTO user_event (User_ID, Event_ID_Event) VALUES (?, ?)");
        $insert->execute([$user_id, $event_id]);

        // Simpan transaksi dummy (jika mau catat pembayaran)
        $trx = $pdo->prepare("INSERT INTO transaksi 
            (ID_Transaksi, Tanggal_Pemesanan, Total_Awal, Diskon, Total_Akhir, Status_Pembayaran, Metode_Pembayaran, User_ID, Paket_ID_Paket) 
            VALUES (?, CURDATE(), ?, 0, ?, 'Lunas', ?, ?, 'PKT1')");
        $trx->execute([
            uniqid("TR"), $event["Biaya_Pendaftaran"], $event["Biaya_Pendaftaran"],
            $metode, $user_id
        ]);

        // Redirect agar tidak double submit saat refresh
        header("Location: join_event.php?id=$event_id&status=success");
        exit();
    } else {
        $pesan = "Kamu sudah terdaftar di event ini.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Join Event</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="card p-4 shadow-sm">
    <h3 class="mb-3">📅 Pendaftaran Event: <?= htmlspecialchars($event["Nama_Event"]) ?></h3>
    <p><?= htmlspecialchars($event["Deskripsi_Event"]) ?></p>
    <p><strong>Biaya:</strong> Rp<?= number_format($event["Biaya_Pendaftaran"], 0, ',', '.') ?></p>

    <?php if (!empty($pesan)): ?>
      <div class="alert alert-info mt-3"><?= $pesan ?></div>
    <?php else: ?>
      <form method="post" class="mt-4">
        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">

        <div class="mb-3">
          <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
          <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
            <option value="QRIS">QRIS</option>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="E-Wallet">E-Wallet</option>
          </select>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success">Bayar & Daftar</button>
          <a href="event_list.php" class="btn btn-secondary">← Kembali</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
