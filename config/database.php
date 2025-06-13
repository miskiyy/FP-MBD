<?php
$host = 'localhost';
$dbname = 'test';  // ganti sesuai nama database kamu
$username = 'root';
$password = '';     // kosongkan jika pakai XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
