<?php
session_start();
require_once('../../config/database.php'); // PDO tersedia di $pdo

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");

    exit();
}

$stmt = $pdo->query("SELECT * FROM courses");

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Course</title>
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
                <h2>📚 Manajemen Course</h2>
                <a href="add_course.php" class="btn btn-primary btn-sm">+ Tambah Course</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Rating</th>
                            <th>Tingkat Kesulitan</th>
                            <th>ID Sertifikat</th>
                            <th>NIK Karyawan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['ID_courses']) ?> </td>
                                <td><?= htmlspecialchars($row['Nama_course']) ?> </td>
                                <td><?= htmlspecialchars($row['Rating_course']) ?> </td>
                                <td><?= htmlspecialchars($row['Tingkat_kesulitan']) ?> </td>
                                <td><?= htmlspecialchars($row['Sertifikat_ID_sertifikat']) ?> </td>
                                <td><?= htmlspecialchars($row['Karyawan_NIK']) ?> </td>
                                <td>
                                   <a href="edit_course.php?id=<?= urlencode($row['ID_courses']) ?>" 
                                      class="btn btn-warning btn-sm btn-action">Edit</a> 
                                   <a href="delete_course.php?id=<?= urlencode($row['ID_courses']) ?>" 
                                      class="btn btn-danger btn-sm btn-action" 
                                      onclick="return confirm('Yakin mau hapus course ini?');">
                                      Hapus
                                   </a>
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
