<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$error = "";
$success = "";
$queryKaryawan = "SELECT NIK, First_Name, Last_Name FROM karyawan";
$resultKaryawan = mysqli_query($conn, $queryKaryawan);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["ID_courses"];
    $nama = $_POST["Nama_course"];
    // $rating = $_POST["Rating_course"];
    $tingkat = $_POST["Tingkat_kesulitan"];
    $sertifikat = $_POST["Sertifikat_ID_sertifikat"];
    $nik = $_POST["Karyawan_NIK"];

    // Validasi singkat
    if ($id && $nama && $tingkat && $sertifikat && $nik) {
        $query = "INSERT INTO courses (ID_courses, Nama_course, Rating_course, Tingkat_kesulitan, Sertifikat_ID_sertifikat, Karyawan_NIK) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdsss", $id, $nama, $rating, $tingkat, $sertifikat, $nik);

        if ($stmt->execute()) {
            $success = "Course berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan course: " . $stmt->error;
        }
    } else {
        $error = "Semua field kecuali rating wajib diisi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Course</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <h2>Tambah Course Baru</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <form method="post" action="add_course.php">
        <label>ID Course:</label><br>
        <input type="text" name="ID_courses" required><br>

        <label>Nama Course:</label><br>
        <input type="text" name="Nama_course" required><br>

        <label>Tingkat Kesulitan:</label><br>
        <input type="range" name="Tingkat_kesulitan" min="1" max="10" step="0.1" value="5" oninput="this.nextElementSibling.value = this.value">
        <output>5</output><br>

        <label>ID Sertifikat:</label><br>
        <input type="text" name="Sertifikat_ID_sertifikat" value="notassign" readonly><br>

        <label>Karyawan (NIK):</label><br>
        <select name="Karyawan_NIK" required>
            <option value="">-- Pilih Karyawan --</option>
            <?php while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
                <option value="<?= $row['NIK'] ?>">
                    <?= $row['NIK'] ?> - <?= $row['First_Name'] . ' ' . $row['Last_Name'] ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <br><button type="submit">Tambah Course</button>
    </form>


    <br>
    <a href="manage_course.php">Kembali ke daftar course</a>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="Karyawan_NIK"]').select2();
        });
    </script>
</body>
</html>
