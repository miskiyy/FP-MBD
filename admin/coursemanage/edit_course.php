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

$sertifikat = $conn->query("
    SELECT ID_Sertifikat, Nama_Sertifikat
    FROM sertifikat
    WHERE ID_Sertifikat = 'notassig'
       OR Nama_Sertifikat NOT LIKE 'Event:%'
");

if (!$course) {
    $error = "Course tidak ditemukan.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["Nama_course"];
    $rating = isset($_POST["Rating_course"]) ? $_POST["Rating_course"] : ($course["Rating_course"] ?? 0);
    $tingkat = $_POST["Tingkat_kesulitan"];
    $sertifikat_id = $_POST["Sertifikat_ID_sertifikat"];
    $nik = $_POST["Karyawan_NIK"];

    $queryUpdate = "UPDATE courses SET Nama_course = ?, Rating_course = ?, Tingkat_kesulitan = ?, Sertifikat_ID_sertifikat = ?, Karyawan_NIK = ? WHERE ID_courses = ?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("sdssss", $nama, $rating, $tingkat, $sertifikat_id, $nik, $id);

    if ($stmtUpdate->execute()) {
        $success = "Course berhasil diperbarui!";
        // Refresh data
        $course["Nama_course"] = $nama;
        $course["Rating_course"] = $rating;
        $course["Tingkat_kesulitan"] = $tingkat;
        $course["Sertifikat_ID_sertifikat"] = $sertifikat_id;
        $course["Karyawan_NIK"] = $nik;
    } else {
        $error = "Gagal update: " . $stmtUpdate->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Course - CodingIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body { font-family: "Inter", sans-serif; }
        .select2-container .select2-selection--single {
            height: 42px !important;
            padding: 6px 12px;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            line-height: 28px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-3xl ml-0 md:ml-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-purple-700">Edit Course</h2>
                <a href="manage_course.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($course): ?>
            <form method="post" action="edit_course.php?id=<?= $course['ID_courses'] ?>" class="space-y-4">
                <input type="hidden" name="ID_courses" value="<?= htmlspecialchars($course['ID_courses']) ?>">

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Course</label>
                    <input type="text" name="Nama_course" value="<?= htmlspecialchars($course['Nama_course']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tingkat Kesulitan</label>
                    <input type="range" name="Tingkat_kesulitan" min="1" max="10" step="0.1" value="<?= htmlspecialchars($course['Tingkat_kesulitan']) ?>" oninput="this.nextElementSibling.value = this.value" class="w-full">
                    <output class="ml-2"><?= htmlspecialchars($course['Tingkat_kesulitan']) ?></output>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Sertifikat</label>
                    <select name="Sertifikat_ID_sertifikat" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Sertifikat --</option>
                        <?php
                        // Reset pointer sertifikat
                        $sertifikat->data_seek(0);
                        while ($s = $sertifikat->fetch_assoc()): ?>
                            <option value="<?= $s['ID_Sertifikat'] ?>" <?= $course['Sertifikat_ID_sertifikat'] == $s['ID_Sertifikat'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['Nama_Sertifikat']) ?> (<?= htmlspecialchars($s['ID_Sertifikat']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Karyawan (NIK)</label>
                    <select name="Karyawan_NIK" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Karyawan --</option>
                        <?php
                        // Reset pointer karyawan
                        mysqli_data_seek($resultKaryawan, 0);
                        while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
                            <option value="<?= $row['NIK'] ?>" <?= $course['Karyawan_NIK'] == $row['NIK'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['NIK']) ?> - <?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Simpan Perubahan</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="Karyawan_NIK"]').select2();
        });
    </script>
</body>
</html>