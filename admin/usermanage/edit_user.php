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
        header("Location: manage_user.php");
        exit();
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>

<h2>Edit User</h2>
<form method="post">
    Nama Depan: <input type="text" name="fname" value="<?= $data['First_Name'] ?>" required><br>
    Nama Belakang: <input type="text" name="lname" value="<?= $data['Last_Name'] ?>"><br>
    Jenis Kelamin: <input type="text" name="jk" value="<?= $data['Jenis_kelamin'] ?>" required><br>
    Pekerjaan: <input type="text" name="pekerjaan" value="<?= $data['Pekerjaan'] ?>" required><br>
    Kota: <input type="text" name="kota" value="<?= $data['Kota'] ?>" required><br>
    Negara: <input type="text" name="negara" value="<?= $data['Negara'] ?>" required><br>
    Telepon: <input type="text" name="telepon" value="<?= $data['Nomor_Telepon'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $data['Email'] ?>" required><br>
    Tentang Saya: <input type="text" name="tentang" value="<?= $data['Tentang_Saya'] ?>"><br>
    Tanggal Lahir: <input type="date" name="tanggal_lahir" value="<?= $data['Tanggal_Lahir'] ?>" required><br>
    Password: <input type="text" name="password" value="<?= $data['password'] ?>" required><br>
    <button type="submit">Simpan Perubahan</button>
</form>
