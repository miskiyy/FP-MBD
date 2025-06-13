<?php
class Paket {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllPaket() {
        $stmt = $this->pdo->query("SELECT * FROM paket");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPaket($data) {
        $stmt = $this->pdo->prepare("INSERT INTO paket (ID_paket, Nama_paket, Durasi_paket, Harga) VALUES (?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function deletePaket($id) {
        $stmt = $this->pdo->prepare("DELETE FROM paket WHERE ID_paket = ?");
        return $stmt->execute([$id]);
    }

    public function updatePaket($data) {
        $stmt = $this->pdo->prepare("UPDATE paket SET Nama_paket = ?, Durasi_paket = ?, Harga = ? WHERE ID_paket = ?");
        return $stmt->execute($data);
    }

    public function getPaketById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM paket WHERE ID_paket = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
