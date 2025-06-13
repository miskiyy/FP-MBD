<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

// Gunakan PDO
$stmt = $pdo->query("SELECT * FROM user");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    h2 {
      font-weight: 600;
    }
    .table-container {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      padding: 24px;
      animation: fadeIn 0.5s ease-in-out;
    }
    .btn-add {
      margin-bottom: 20px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    table td, table th {
      vertical-align: middle !important;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center py-5">
  <div class="table-container w-100" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>📋 Daftar User</h2>
      <a href="add_user.php" class="btn btn-primary btn-sm btn-add">+ Tambah User Baru</a>
    </div>

    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Kota</th>
          <th>Negara</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['ID']) ?></td>
          <td><?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) ?></td>
          <td><?= htmlspecialchars($row['Email']) ?></td>
          <td><?= htmlspecialchars($row['Kota']) ?></td>
          <td><?= htmlspecialchars($row['Negara']) ?></td>
          <td>
            <a href="edit_user.php?id=<?= urlencode($row['ID']) ?>" class="text-primary">Edit</a> |
            <a href="delete_user.php?id=<?= urlencode($row['ID']) ?>" class="text-danger" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</div>

</body>
</html>
