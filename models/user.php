<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM user");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE Email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addUser($data) {
        $stmt = $this->pdo->prepare("INSERT INTO user 
            (ID, First_Name, Last_Name, Jenis_kelamin, Pekerjaan, Kota, Negara, Nomor_Telepon, Email, Tentang_Saya, Tanggal_Lahir, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE ID = ?");
        return $stmt->execute([$id]);
    }

    public function updateUser($data) {
        $stmt = $this->pdo->prepare("UPDATE user SET First_Name = ?, Last_Name = ?, Email = ? WHERE ID = ?");
        return $stmt->execute($data);
    }
}
