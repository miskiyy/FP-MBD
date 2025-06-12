<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST["ID_courses"] : $_GET["id"];
$error = "";
$success = "";

// Ambil data course
$query = "SELECT * FROM courses WHERE ID_courses = ?";
$queryKaryawan = "SELECT NIK, First_Name, Last_Name FROM karyawan";
$resultKaryawan = mysqli_query($conn, $queryKaryawan);
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    $error = "Course tidak ditemukan.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["Nama_course"];
    $rating = $_POST["Rating_course"];
    $tingkat = $_POST["Tingkat_kesulitan"];
    $sertifikat = $_POST["Sertifikat_ID_sertifikat"];
    $nik = $_POST["Karyawan_NIK"];

    $queryUpdate = "UPDATE courses SET Nama_course = ?, Rating_course = ?, Tingkat_kesulitan = ?, Sertifikat_ID_sertifikat = ?, Karyawan_NIK = ? WHERE ID_courses = ?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("sdssss", $nama, $rating, $tingkat, $sertifikat, $nik, $id);

    if ($stmtUpdate->execute()) {
        $success = "Course berhasil diperbarui!";
        // Refresh data
        $course["Nama_course"] = $nama;
        $course["Rating_course"] = $rating;
        $course["Tingkat_kesulitan"] = $tingkat;
        $course["Sertifikat_ID_sertifikat"] = $sertifikat;
        $course["Karyawan_NIK"] = $nik;
    } else {
        $error = "Gagal update: " . $stmtUpdate->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <h2>Edit Course</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <form method="post" action="edit_course.php?id=<?= $course['ID_courses'] ?>">
        <input type="hidden" name="ID_courses" value="<?= $course['ID_courses'] ?>">

        <label>Nama Course:</label><br>
        <input type="text" name="Nama_course" value="<?= $course['Nama_course'] ?>" required><br>

        <label>Tingkat Kesulitan:</label><br>
        <input type="range" name="Tingkat_kesulitan" min="1" max="10" step="0.1" value="<?= $course['Tingkat_kesulitan'] ?>" oninput="this.nextElementSibling.value = this.value">
        <output><?= $course['Tingkat_kesulitan'] ?></output><br>

        <label>ID Sertifikat:</label><br>
        <input type="text" name="Sertifikat_ID_sertifikat" value="notassign" readonly><br>
        
        <label>Karyawan (NIK):</label><br>
        <select name="Karyawan_NIK" required>
            <option value="">-- Pilih Karyawan --</option>
            <?php while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
                <option value="<?= $row['NIK'] ?>" <?= $course['Karyawan_NIK'] == $row['NIK'] ? 'selected' : '' ?>>
                    <?= $row['NIK'] ?> - <?= $row['First_Name'] . ' ' . $row['Last_Name'] ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <br><button type="submit">Simpan Perubahan</button>
    </form>

    <br>
    <a href="manage_course.php">Kembali ke daftar course</a>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="Karyawan_NIK"]').select2();
        });
</body>
</html>
