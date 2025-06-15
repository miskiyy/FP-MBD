<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION["user_id"]) || !isset($_GET["id_paket"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$paket_id = $_GET["id_paket"];

$stmt = $pdo->prepare("SELECT * FROM paket WHERE ID_paket = ?");
$stmt->execute([$paket_id]);
$paket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paket) {
    echo "Paket tidak ditemukan.";
    exit();
}

$nama_paket = $paket['Nama_paket'];
$harga = $paket['Harga'];
$durasi = $paket['Durasi_paket'];

$redeem_code = $_POST['redeem_code'] ?? '';
$diskon = 0;
$total_akhir = $harga;
$error = '';
$info = '';

// Tombol ditekan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['apply_code'])) {
        // hanya apply kode
        if ($redeem_code) {
            $cek = $pdo->prepare("SELECT Diskon FROM redeem_code WHERE Kode = ?");
            $cek->execute([$redeem_code]);
            $result = $cek->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $diskon = $result['Diskon'];
                $total_akhir = $harga - ($harga * $diskon);
                $info = "Kode berhasil diterapkan!";
            } else {
                $error = "Kode tidak ditemukan 😢";
                $redeem_code = '';
            }
        }
    } elseif (isset($_POST['submit_transaksi'])) {
        // validasi ulang kode (biar aman)
        if ($redeem_code) {
            $cek = $pdo->prepare("SELECT Diskon FROM redeem_code WHERE Kode = ?");
            $cek->execute([$redeem_code]);
            $result = $cek->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $diskon = $result['Diskon'];
            } else {
                $redeem_code = null;
                $diskon = 0;
                $total_akhir = $harga;
            }
        }

        $last = $pdo->query("SELECT ID_Transaksi FROM transaksi ORDER BY ID_Transaksi DESC LIMIT 1")->fetchColumn();
        $nextNumber = $last ? (int)substr($last, 2) + 1 : 1;
        $id_transaksi = "TR" . str_pad($nextNumber, 4, "0", STR_PAD_LEFT);

        $stmt = $pdo->prepare("INSERT INTO transaksi 
            (ID_Transaksi, Tanggal_Pemesanan, Total_Awal, REDEEM_CODE, Diskon, Total_Akhir,
            Status_Pembayaran, Tanggal_Dimulai, Tanggal_Berakhir, User_ID, Paket_ID_Paket)
            VALUES (?, CURDATE(), ?, ?, ?, NULL, 'Menunggu Pembayaran', NULL, NULL, ?, ?)");

        $stmt->execute([
            $id_transaksi,
            $harga,
            $redeem_code,
            $diskon,
            $user_id,
            $paket_id
        ]);



        header("Location: status_transaksi.php?id=$id_transaksi");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9f7ff;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 2rem;
    }

    .checkout-card {
      background: white;
      padding: 2rem;
      border-radius: 1.5rem;
      box-shadow: 0 10px 15px -3px rgb(167 139 250 / 0.3);
      width: 100%;
      max-width: 500px;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #4c1d95;
    }

    label {
      display: block;
      margin-top: 1rem;
      font-weight: 600;
      color: #374151;
    }

    input[type="text"] {
      width: 100%;
      padding: 0.5rem 0.75rem;
      border: 2px solid #ddd6fe;
      border-radius: 999px;
      margin-top: 0.25rem;
    }

    .summary, .rek-info {
      margin-top: 1.5rem;
      font-size: 0.9rem;
    }

    .summary p, .rek-info p {
      margin: 0.3rem 0;
    }

    .rek-info {
      background: #f3f4f6;
      padding: 1rem;
      border-radius: 0.75rem;
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
      display: block;
      width: 100%;
    }

    .btn:hover {
      background-color: #6d28d9;
    }

    .message {
      margin-top: 1rem;
      font-size: 0.875rem;
      font-weight: 600;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      text-align: center;
    }

    .error {
      background: #fee2e2;
      color: #b91c1c;
    }

    .success {
      background: #d1fae5;
      color: #065f46;
    }

    .code-actions {
      display: flex;
      gap: 1rem;
      align-items: center;
      margin-top: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="checkout-card">
    <h2>🧾 Checkout Pembayaran</h2>
    <form method="POST">
      <p><strong>Paket:</strong> <?= htmlspecialchars($nama_paket) ?></p>
      <p><strong>Durasi:</strong> <?= $durasi ?> bulan</p>

      <label for="redeem_code">Kode Redeem (opsional)</label>
      <div class="code-actions">
        <input type="text" name="redeem_code" id="redeem_code" value="<?= htmlspecialchars($redeem_code) ?>" placeholder="Masukkan kode...">
        <button type="submit" name="apply_code" class="btn" style="width: auto; padding: 0.4rem 1rem;">Terapkan</button>
      </div>

      <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
      <?php elseif ($info): ?>
        <div class="message success"><?= $info ?></div>
      <?php endif; ?>

      <div class="summary">
        <p><strong>Total Awal:</strong> Rp<?= number_format($harga, 0, ',', '.') ?></p>
        <p><strong>Diskon:</strong> <?= $diskon * 100 ?>%</p>
        <p><strong>Total Akhir:</strong> Rp<?= number_format($total_akhir, 0, ',', '.') ?></p>
      </div>

      <div class="rek-info">
        <p>💳 Transfer ke rekening berikut:</p>
        <p><strong>BCA 123456789 a.n. CODINGIN ACADEMY</strong></p>
        <p>Setelah transfer, lanjutkan ke halaman konfirmasi.</p>
      </div>

      <button type="submit" name="submit_transaksi" class="btn">Lanjutkan Pembayaran</button>
    </form>
  </div>
</body>
</html>