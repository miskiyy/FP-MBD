<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_challenge.php");
    exit();
}

$id = $_GET["id"];
$query = "SELECT * FROM challenge WHERE id_challenge = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$challenge = $result->fetch_assoc();

if (!$challenge) {
    die("Challenge tidak ditemukan.");
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama_challenge"];
    $deskripsi = $_POST["deskripsi"];
    $mulai = $_POST["tanggal_mulai"];
    $akhir = $_POST["tanggal_berakhir"];
    $kuota = $_POST["kuota_pemenang"];
    $hadiah = $_POST["hadiah"];

    $queryUpdate = "UPDATE challenge SET nama_challenge=?, deskripsi=?, tanggal_mulai=?, tanggal_berakhir=?, kuota_pemenang=?, hadiah=? WHERE id_challenge=?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("ssssiss", $nama, $deskripsi, $mulai, $akhir, $kuota, $hadiah, $id);

    if ($stmtUpdate->execute()) {
        $success = "Berhasil diperbarui!";
    } else {
        $error = "Gagal update: " . $stmtUpdate->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Challenge</title></head>
<body>
    <h2>Edit Challenge</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="post">
        <label>Nama:</label><br><input type="text" name="nama_challenge" value="<?= $challenge['nama_challenge'] ?>" required><br>
        <label>Deskripsi:</label><br><textarea name="deskripsi" required><?= $challenge['deskripsi'] ?></textarea><br>
        <label>Tanggal Mulai:</label><br><input type="date" name="tanggal_mulai" value="<?= $challenge['tanggal_mulai'] ?>" required><br>
        <label>Tanggal Berakhir:</label><br><input type="date" name="tanggal_berakhir" value="<?= $challenge['tanggal_berakhir'] ?>" required><br>
        <label>Kuota Pemenang:</label><br><input type="number" name="kuota_pemenang" value="<?= $challenge['kuota_pemenang'] ?>" required><br>
        <label>Hadiah:</label><br><input type="text" name="hadiah" value="<?= $challenge['hadiah'] ?>" required><br><br>
        <button type="submit">Simpan</button>
    </form>
    <br><a href="manage_challenge.php">Kembali</a>
</body>
</html>
