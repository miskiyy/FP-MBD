<?php
class Transaksi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserTransaksi($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM transaksi WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahTransaksi($data) {
        $stmt = $this->pdo->prepare("INSERT INTO transaksi (ID, user_id, tipe, keterangan, tanggal) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute($data);
    }
}
