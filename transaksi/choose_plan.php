<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->query("SELECT * FROM paket");
$paket = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header_user.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pilih Plan</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    body {
      font-family: "Inter", sans-serif;
      background-color: #f9f7ff;
      min-height: 100vh;
      margin: 0;
      padding: 2rem 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h2 {
      font-weight: 700;
      font-size: 1.75rem;
      color: #1f2937;
      margin-bottom: 2rem;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      max-width: 720px;
      width: 100%;
    }

    .plan-card {
      background: white;
      border-radius: 1.5rem;
      box-shadow: 0 10px 15px -3px rgb(167 139 250 / 0.3);
      padding: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      animation: fadeInUp 0.7s ease forwards;
      opacity: 0;
    }

    .plan-card h3 {
      font-size: 1.25rem;
      font-weight: 700;
      color: #4c1d95;
      margin-bottom: 0.5rem;
    }

    .plan-card p {
      font-size: 0.875rem;
      color: #374151;
      margin: 0.25rem 0;
    }

    .plan-card button {
      background-color: #7c3aed;
      color: white;
      font-weight: 600;
      font-size: 0.875rem;
      padding: 0.5rem 1.5rem;
      border-radius: 9999px;
      border: none;
      cursor: pointer;
      transition: background-color 0.2s ease;
      margin-top: 1rem;
      width: 100%;
    }

    .plan-card button:hover {
      background-color: #6d28d9;
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

  <h2>📦 Pilih Plan Premium Kamu</h2>

  <div class="grid-container">

    <?php foreach ($paket as $row): ?>
      <?php
        $emoji = match ($row['Nama_paket']) {
          'Pemula' => '👶',
          'Jago'   => '🧠',
          'Sepuh'  => '🧓',
          'Suhu'   => '🧑🏻‍💻',
          default  => '📦',
      };
      ?>
    <form method="GET" action="checkout.php" class="plan-card">
      <h3><?= $emoji . ' ' . htmlspecialchars($row['Nama_paket']) ?></h3>
      <p>Harga: Rp<?= number_format($row['Harga'], 0, ',', '.') ?></p>
      <p>Durasi Akses: <?= $row['Durasi_paket'] ?> bulan</p>
      <input type="hidden" name="id_paket" value="<?= htmlspecialchars($row['ID_paket']) ?>">
      <button type="submit">Gabung Sekarang</button>
    </form>
    <?php endforeach; ?>

  </div>

</body>
</html>

<?php include '../includes/footer_user.php'; ?>