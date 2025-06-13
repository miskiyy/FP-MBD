<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM event");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 24px;
            animation: fadeIn 0.5s ease-in-out;
        }
        h2 {
            font-weight: 600;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
    <div class="card-container w-100" style="max-width: 1200px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>📅 Manajemen Event</h2>
            <a href="add_event.php" class="btn btn-primary btn-sm">+ Tambah Event</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Deskripsi</th>
                        <th>Lokasi</th>
                        <th>Biaya</th>
                        <th>Kuota</th>
                        <th>Mulai</th>
                        <th>Berakhir</th>
                        <th>ID Sertifikat</th>
                        <th>NIK Karyawan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ID_event']) ?></td>
                            <td><?= htmlspecialchars($row['Nama_Event']) ?></td>
                            <td><?= htmlspecialchars($row['Jenis_Event']) ?></td>
                            <td><?= htmlspecialchars($row['Deskripsi_Event']) ?></td>
                            <td><?= htmlspecialchars($row['Lokasi_Acara']) ?></td>
                            <td>Rp<?= number_format($row['Biaya_Pendaftaran'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['Kuota_Pendaftaran']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_mulai_event']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_berakhir_event']) ?></td>
                            <td><?= htmlspecialchars($row['Sertifikat_ID_Sertifikat']) ?></td>
                            <td><?= htmlspecialchars($row['Karyawan_NIK']) ?></td>
                            <td>
                                <a href="edit_event.php?id=<?= urlencode($row['ID_event']) ?>" class="btn btn-warning btn-sm btn-action">Edit</a>
                                <a href="delete_event.php?id=<?= urlencode($row['ID_event']) ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Yakin mau hapus event ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
