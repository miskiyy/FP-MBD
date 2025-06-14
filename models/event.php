<?php
class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM event");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM event WHERE ID_event = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $stmt = $this->pdo->prepare("INSERT INTO event (ID_event, Nama_Event, Jenis_Event, Deskripsi_Event, Lokasi_Acara, Biaya_Pendaftaran, Kuota_Pendaftaran, tanggal_mulai_event, tanggal_berakhir_event, Sertifikat_ID_Sertifikat, Karyawan_NIK) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function update($data) {
        $stmt = $this->pdo->prepare("UPDATE event SET Nama_Event=?, Jenis_Event=?, Deskripsi_Event=?, Lokasi_Acara=?, Biaya_Pendaftaran=?, Kuota_Pendaftaran=?, tanggal_mulai_event=?, tanggal_berakhir_event=?, Sertifikat_ID_Sertifikat=?, Karyawan_NIK=? WHERE ID_event=?");
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM event WHERE ID_event = ?");
        return $stmt->execute([$id]);
    }
}