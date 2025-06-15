<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
  header("Location: ../public/login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$id_transaksi = $_GET['id'];

// Ambil detail transaksi
$stmt = $pdo->prepare("SELECT t.*, p.Nama_paket 
                       FROM transaksi t
                       JOIN paket p ON t.Paket_ID_Paket = p.ID_paket
                       WHERE t.ID_Transaksi = ? AND t.User_ID = ?");
$stmt->execute([$id_transaksi, $user_id]);
$transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
  echo "Transaksi tidak ditemukan.";
  exit();
}

// Jika sudah upload bukti, langsung redirect ke dashboard
if (!empty($transaksi['Bukti_Pembayaran'])) {
  header("Location: ../public/dashboard_user.php");
  exit();
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti'])) {
  $nama_file = basename($_FILES['bukti']['name']);
  $tujuan = '../uploads/' . $nama_file;
  move_uploaded_file($_FILES['bukti']['tmp_name'], $tujuan);

  $update = $pdo->prepare("UPDATE transaksi 
                           SET Bukti_Pembayaran = ?, Status_Pembayaran = 'Lunas' 
                           WHERE ID_Transaksi = ?");
  $update->execute([$nama_file, $id_transaksi]);

  header("Location: ../public/dashboard_user.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Status Transaksi</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9f7ff;
      display: flex;
      justify-content: center;
      padding: 2rem;
    }
    .status-card {
      background: white;
      padding: 2rem;
      border-radius: 1.5rem;
      box-shadow: 0 10px 15px -3px rgba(167, 139, 250, 0.3);
      width: 100%;
      max-width: 600px;
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      text-align: center;
      color: #4c1d95;
      margin-bottom: 1rem;
    }
    .detail p {
      margin: 0.5rem 0;
      color: #374151;
      font-size: 0.95rem;
    }
    .upload-form {
      margin-top: 1.5rem;
    }
    input[type="file"] {
      display: block;
      margin-top: 0.5rem;
      font-family: inherit;
    }
    .btn {
      background-color: #7c3aed;
      color: white;
      font-weight: 600;
      font-size: 0.875rem;
      padding: 0.6rem 1.5rem;
      border-radius: 9999px;
      border: none;
      cursor: pointer;
      margin-top: 1rem;
      width: 100%;
    }
    .btn:hover {
      background-color: #6d28d9;
    }
    .success-msg {
      margin-top: 1.2rem;
      font-size: 0.875rem;
      font-weight: 600;
      background-color: #d1fae5;
      color: #065f46;
      padding: 0.75rem 1rem;
      border-radius: 0.75rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="status-card">
    <h2>📄 Status Transaksi</h2>
    <div class="detail">
      <p><strong>ID Transaksi:</strong> <?= htmlspecialchars($transaksi['ID_Transaksi']) ?></p>
      <p><strong>Paket:</strong> <?= htmlspecialchars($transaksi['Nama_paket']) ?></p>
      <p><strong>Total Akhir:</strong> Rp<?= number_format($transaksi['Total_Akhir'], 0, ',', '.') ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($transaksi['Status_Pembayaran']) ?></p>
    </div>

    <form class="upload-form" method="POST" enctype="multipart/form-data">
      <label for="bukti">Upload Bukti Pembayaran</label><br>
      <input type="file" name="bukti" accept="image/*" required>
      <button type="submit" class="btn">Kirim Bukti</button>
    </form>
  </div>
</body>
</html>