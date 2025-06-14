<?php
session_start();
require_once '../../config/database.php';
require_once '../helpers/id_generator.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$error = "";
$success = "";

$queryKaryawan = "SELECT NIK, First_Name, Last_Name FROM karyawan";
$resultKaryawan = mysqli_query($conn, $queryKaryawan);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = generateIDUnique($conn, 'courses', 'ID_courses', 'CRS', 4); // CRSxxxx
    $sertif_id = generateIDUnique($conn, 'sertifikat', 'ID_Sertifikat', 'SRF', 4); // SRFxxxx

    $nama = $_POST["Nama_course"];
    $rating = isset($_POST["Rating_course"]) ? $_POST["Rating_course"] : 0;
    $tingkat = $_POST["Tingkat_kesulitan"];
    $nik = $_POST["Karyawan_NIK"];

    // Validasi
    if ($nama && $tingkat && $nik) {
        // 1. Insert Sertifikat kosong dulu
        $stmtSertif = $conn->prepare("INSERT INTO sertifikat (ID_Sertifikat, Nama_Sertifikat) VALUES (?, ?)");
        $defaultNama = "Course: " . $nama;
        $stmtSertif->bind_param("ss", $sertif_id, $defaultNama);
        $sertifInserted = $stmtSertif->execute();

        if (!$sertifInserted) {
            $error = "Gagal membuat sertifikat: " . $stmtSertif->error;
        } else {
            // 2. Insert Course
            $stmt = $conn->prepare("INSERT INTO courses 
                (ID_courses, Nama_course, Rating_course, Tingkat_kesulitan, Sertifikat_ID_sertifikat, Karyawan_NIK)
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsss", $id, $nama, $rating, $tingkat, $sertif_id, $nik);

            if ($stmt->execute()) {
                $success = "Course berhasil ditambahkan! Sertifikat ID: $sertif_id";
            } else {
                $error = "Gagal menambahkan course: " . $stmt->error;
            }
        }
    } else {
        $error = "Semua field kecuali rating wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Course - CodingIn</title>
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
                <h2 class="text-2xl font-bold text-purple-700">Tambah Course Baru</h2>
                <a href="manage_course.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <form method="post" action="add_course.php" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Course</label>
                    <input type="text" name="Nama_course" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tingkat Kesulitan</label>
                    <input type="range" name="Tingkat_kesulitan" min="1" max="10" step="0.1" value="5" oninput="this.nextElementSibling.value = this.value" class="w-full">
                    <output class="ml-2">5</output>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Rating (Opsional)</label>
                    <input type="number" step="0.1" name="Rating_course" placeholder="Misal 8.2" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Karyawan (NIK)</label>
                    <select name="Karyawan_NIK" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <option value="">-- Pilih Karyawan --</option>
                        <?php
                        mysqli_data_seek($resultKaryawan, 0);
                        while ($row = mysqli_fetch_assoc($resultKaryawan)): ?>
                            <option value="<?= $row['NIK'] ?>">
                                <?= htmlspecialchars($row['NIK']) ?> - <?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Tambah Course</button>
                </div>
            </form>
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