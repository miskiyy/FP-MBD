<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM challenge");
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Challenge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fbfd;
        }
        .card-container {
            background-color: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        h2 {
            font-weight: 600;
        }
        .btn-sm {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>🏆 Daftar Challenge</h2>
            <a href="add_challenge.php" class="btn btn-primary btn-sm">+ Tambah Challenge</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Berakhir</th>
                        <th>Kuota</th>
                        <th>Hadiah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($challenges as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_challenge']) ?></td>
                            <td><?= htmlspecialchars($row['nama_challenge']) ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_berakhir']) ?></td>
                            <td><?= htmlspecialchars($row['kuota_pemenang']) ?></td>
                            <td><?= htmlspecialchars($row['hadiah']) ?></td>
                            <td>
                                <a href="edit_challenge.php?id=<?= urlencode($row['id_challenge']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_challenge.php?id=<?= urlencode($row['id_challenge']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($challenges)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada challenge yang tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
