<?php
class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllEvents() {
        $stmt = $this->pdo->query("SELECT * FROM event");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addEvent($data) {
        $stmt = $this->pdo->prepare("INSERT INTO event (ID, Nama_Event, Tanggal_Event, Lokasi) VALUES (?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function deleteEvent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM event WHERE ID = ?");
        return $stmt->execute([$id]);
    }

    public function updateEvent($data) {
        $stmt = $this->pdo->prepare("UPDATE event SET Nama_Event = ?, Tanggal_Event = ?, Lokasi = ? WHERE ID = ?");
        return $stmt->execute($data);
    }
}
