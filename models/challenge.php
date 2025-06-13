<?php
class Challenge {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllChallenges() {
        $stmt = $this->pdo->query("SELECT * FROM challenge");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChallengeById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM challenge WHERE ID = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addChallenge($data) {
        $stmt = $this->pdo->prepare("INSERT INTO challenge (ID, Nama, Deskripsi, Tanggal) VALUES (?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function deleteChallenge($id) {
        $stmt = $this->pdo->prepare("DELETE FROM challenge WHERE ID = ?");
        return $stmt->execute([$id]);
    }

    public function updateChallenge($data) {
        $stmt = $this->pdo->prepare("UPDATE challenge SET Nama = ?, Deskripsi = ?, Tanggal = ? WHERE ID = ?");
        return $stmt->execute($data);
    }
}
