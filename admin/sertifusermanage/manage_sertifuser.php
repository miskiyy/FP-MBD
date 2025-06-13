<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$tipe = $_GET["tipe"] ?? "event";

// Query berdasarkan tipe
if ($tipe == "course") {
    $query = "
        SELECT 
            u.ID AS User_ID,
            CONCAT(u.First_Name, ' ', u.Last_Name) AS Nama,
            c.Nama_course,
            s.ID_Sertifikat,
            s.Nama_Sertifikat,
            su.tanggal_diberikan
        FROM user u
        JOIN user_course uc ON uc.User_ID = u.ID
        JOIN courses c ON c.ID_courses = uc.Courses_ID_Courses
        JOIN sertifikat s ON s.ID_Sertifikat = c.Sertifikat_ID_sertifikat
        LEFT JOIN sertifuser su 
            ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        ORDER BY u.ID
    ";
} else {
    $query = "
        SELECT 
            u.ID AS User_ID,
            CONCAT(u.First_Name, ' ', u.Last_Name) AS Nama,
            e.Nama_Event,
            s.ID_Sertifikat,
            s.Nama_Sertifikat,
            su.tanggal_diberikan
        FROM user u
        JOIN user_event ue ON ue.User_ID = u.ID
        JOIN event e ON e.ID_event = ue.Event_ID_Event
        JOIN sertifikat s ON s.ID_Sertifikat = e.Sertifikat_ID_Sertifikat
        LEFT JOIN sertifuser su 
            ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
        ORDER BY u.ID
    ";
}

$stmt = $pdo->query($query);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Sertifikat User</title>
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
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      font-weight: 600;
    }
    .btn-action {
      font-size: 0.875rem;
      padding: 2px 8px;
    }
  </style>
</head>
<body>

<div class="container py-5 d-flex justify-content-center">
  <div class="card-container w-100" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>🎓 Manajemen Sertifikat User (<?= ucfirst($tipe) ?> Based)</h2>
      <div>
        <a href="?tipe=event" class="btn btn-outline-secondary btn-sm me-1">🔖 Event</a>
        <a href="?tipe=course" class="btn btn-outline-secondary btn-sm">📚 Course</a>
      </div>
    </div>

    <div class="mb-3">
      <a href="add_sertifuser.php" class="btn btn-primary btn-sm">+ Assign Sertifikat</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>User ID</th>
            <th>Nama</th>
            <th>Sertifikat</th>
            <th>ID Sertifikat</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php foreach ($rows as $row): ?>
            <?php $sudah_terbit = !is_null($row['tanggal_diberikan']); ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['User_ID']) ?></td>
              <td><?= htmlspecialchars($row['Nama']) ?></td>
              <td><?= htmlspecialchars($row['Nama_Sertifikat']) ?></td>
              <td><?= htmlspecialchars($row['ID_Sertifikat']) ?></td>
              <td>
                <?php if ($sudah_terbit): ?>
                  <span class="text-success">Sudah Diberikan</span><br>
                  <small><?= htmlspecialchars($row['tanggal_diberikan']) ?></small>
                <?php else: ?>
                  <span class="text-danger">Belum</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!$sudah_terbit): ?>
                  <form action="give_sertifuser.php" method="post" class="d-inline">
                    <input type="hidden" name="user_id" value="<?= $row['User_ID'] ?>">
                    <input type="hidden" name="sertif_id" value="<?= $row['ID_Sertifikat'] ?>">
                    <button type="submit" class="btn btn-success btn-action">Terbitkan</button>
                  </form>
                <?php else: ?>
                  <form action="cancel_sertifuser.php" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin mencabut sertifikat ini?');">
                    <input type="hidden" name="user_id" value="<?= $row['User_ID'] ?>">
                    <input type="hidden" name="sertif_id" value="<?= $row['ID_Sertifikat'] ?>">
                    <button type="submit" class="btn btn-danger btn-action">Cabut</button>
                  </form>
                <?php endif; ?>
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
