<?php
require_once('../../config/database.php'); // PDO tersedia di $pdo
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

$stmt = $pdo->prepare("SELECT * FROM transaksi");

$stmt->execute();

$transaksis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Transaksi</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        .card-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.05);
            padding: 24px;
            animation: fadeIn 0.5s ease-in-out;
        }
        h2 {
            font-weight: 600;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(10px);}
            to {opacity: 1; transform: translateY(0);}
        }
        .btn-action {
            font-size: 0.875rem;
            padding: 2px 8px;
        }
        .table-responsive th, .table-responsive td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>

<div class="container py-5 d-flex justify-content-center">
    <div class="card-container w-100" style="max-width: 1200px">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>📝 Manajemen Transaksi</h2>
            <div>
                <a href="add_transaksi.php" class="btn btn-primary btn-sm">+ Tambah Transaksi</a>
                <!-- Tombol Laporan Transaksi jika memang diberlakukan -->
                <!-- <a href="transaction_report.php" class="btn btn-secondary btn-sm">Cetak Laporan Transaksi</a> -->
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Total Awal</th>
                        <th>Redeem Code</th>
                        <th>Diskon</th>
                        <th>Total Akhir</th>
                        <th>Status Pembayaran</th>
                        <th>Metode Pembayaran</th>
                        <th>Tanggal Dimulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>User</th>
                        <th>Paket</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksis as $transaksi): ?>
                        <tr>
                            <td><?= htmlspecialchars($transaksi['ID_Transaksi']) ?> </td>
                            <td><?= htmlspecialchars($transaksi['Tanggal_Pemesanan']) ?> </td>
                            <td>Rp <?= number_format($transaksi['Total_Awal'], 0, ',', '.') ?> </td>
                            <td><?= htmlspecialchars($transaksi['REDEEM_CODE'] ?? '') ?> </td>
                            <td><?= htmlspecialchars($transaksi['Diskon'] ?? '') ?> </td>
                            <td>Rp <?= number_format($transaksi['Total_Akhir'], 0, ',', '.') ?> </td>
                            <td><?= htmlspecialchars($transaksi['Status_Pembayaran']) ?> </td>
                            <td><?= htmlspecialchars($transaksi['Metode_Pembayaran']) ?> </td>
                            <td><?= htmlspecialchars($transaksi['Tanggal_Dimulai'] ?? '') ?> </td>
                            <td><?= htmlspecialchars($transaksi['Tanggal_Berakhir'] ?? '') ?> </td>
                            <td><?= htmlspecialchars($transaksi['User_ID']) ?> </td>
                            <td><?= htmlspecialchars($transaksi['Paket_ID_Paket']) ?> </td>
                            <td>
                                <a href='edit_transaksi.php?id=<?= urlencode($transaksi['ID_Transaksi']) ?>' class='btn btn-warning btn-sm btn-action'>Edit</a>
                                <a href='delete_transaksi.php?id=<?= urlencode($transaksi['ID_Transaksi']) ?>' class='btn btn-danger btn-sm btn-action' onclick='return confirm("Yakin?")'>Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>