<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: login.php");
    exit();
}

echo "Halo admin, selamat datang: " . $_SESSION["NIK"];
?>

<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "karyawan") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>Halo admin, selamat datang: <?= $_SESSION["NIK"] ?></h2>

    <ul>
        <li><a href="../admin/usermanage/manage_user.php">🔧 Manage User</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
</body>
</html>

