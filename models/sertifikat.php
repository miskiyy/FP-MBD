<?php
class Sertifikat {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM sertifikat");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM sertifikat WHERE ID_Sertifikat = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($nama, $template = null, $id = null) {
        if ($id) {
            $stmt = $this->pdo->prepare("INSERT INTO sertifikat (ID_Sertifikat, Nama_Sertifikat, sertif_template) VALUES (?, ?, ?)");
            return $stmt->execute([$id, $nama, $template]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO sertifikat (Nama_Sertifikat, sertif_template) VALUES (?, ?)");
            return $stmt->execute([$nama, $template]);
        }
    }

    public function update($id, $nama, $template) {
        $stmt = $this->pdo->prepare("UPDATE sertifikat SET Nama_Sertifikat = ?, sertif_template = ? WHERE ID_Sertifikat = ?");
        return $stmt->execute([$nama, $template, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sertifikat WHERE ID_Sertifikat = ?");
        return $stmt->execute([$id]);
    }
}