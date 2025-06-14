<?php
$host = 'localhost';
$dbname = 'test';           
$username = 'root';         
$password = '';        

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Koneksi MySQLi gagal: " . mysqli_connect_error());
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi PDO gagal: " . $e->getMessage());
}
?>
