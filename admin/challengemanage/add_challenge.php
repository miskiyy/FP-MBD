<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

$success = "";
$error = "";

function generateID() {
    return "CR" . strtoupper(substr(md5(uniqid()), 0, 4));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = generateID();
    $nama = $_POST["nama_challenge"];
    $deskripsi = $_POST["deskripsi"];
    $mulai = $_POST["tanggal_mulai"];
    $akhir = $_POST["tanggal_berakhir"];
    $kuota = $_POST["kuota_pemenang"];
    $hadiah = $_POST["hadiah"];

    $query = "INSERT INTO challenge VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssis", $id, $nama, $deskripsi, $mulai, $akhir, $kuota, $hadiah);

    if ($stmt->execute()) {
        $success = "Challenge berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan challenge: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Tambah Challenge</title></head>
<body>
    <h2>Tambah Challenge</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="post">
        <label>Nama:</label><br><input type="text" name="nama_challenge" required><br>
        <label>Deskripsi:</label><br><textarea name="deskripsi" required></textarea><br>
        <label>Tanggal Mulai:</label><br><input type="date" name="tanggal_mulai" required><br>
        <label>Tanggal Berakhir:</label><br><input type="date" name="tanggal_berakhir" required><br>
        <label>Kuota Pemenang:</label><br><input type="number" name="kuota_pemenang" required><br>
        <label>Hadiah:</label><br><input type="text" name="hadiah" required><br><br>
        <button type="submit">Tambah</button>
    </form>
    <br><a href="manage_challenge.php">Kembali</a>
</body>
</html>
