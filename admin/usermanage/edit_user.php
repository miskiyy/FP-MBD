<?php
require_once '../../config/database.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM user WHERE ID='$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $jk = $_POST['jk'];
    $pekerjaan = $_POST['pekerjaan'];
    $kota = $_POST['kota'];
    $negara = $_POST['negara'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $tentang = $_POST['tentang'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $password = $_POST['password'];

    $update = "UPDATE user SET 
        First_Name='$fname', Last_Name='$lname', Jenis_kelamin='$jk', 
        Pekerjaan='$pekerjaan', Kota='$kota', Negara='$negara', 
        Nomor_Telepon='$telepon', Email='$email', Tentang_Saya='$tentang', 
        Tanggal_Lahir='$tanggal_lahir', password='$password'
        WHERE ID='$id'";

    if (mysqli_query($conn, $update)) {
        $success = "User berhasil diperbarui!";
        // Refresh data
        $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE ID='$id'"));
    } else {
        $error = "Gagal update: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User - CodingIn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include '../../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-8">
        <div class="bg-white rounded-xl shadow p-8 w-full max-w-lg ml-0 md:ml-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-purple-700">Edit User</h2>
                <a href="manage_user.php" class="text-purple-700 hover:underline text-sm">← Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm"><?= $success ?></div>
            <?php endif; ?>
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Depan</label>
                    <input type="text" name="fname" value="<?= htmlspecialchars($data['First_Name']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Belakang</label>
                    <input type="text" name="lname" value="<?= htmlspecialchars($data['Last_Name']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                    <select name="jk" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" <?= $data['Jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= $data['Jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="<?= htmlspecialchars($data['Pekerjaan']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kota</label>
                    <input type="text" name="kota" value="<?= htmlspecialchars($data['Kota']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Negara</label>
                    <input type="text" name="negara" value="<?= htmlspecialchars($data['Negara']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Telepon</label>
                    <input type="text" name="telepon" value="<?= htmlspecialchars($data['Nomor_Telepon']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($data['Email']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tentang Saya</label>
                    <textarea name="tentang" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200"><?= htmlspecialchars($data['Tentang_Saya']) ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($data['Tanggal_Lahir']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="text" name="password" value="<?= htmlspecialchars($data['password']) ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>