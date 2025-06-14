<?php
class Course {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM courses WHERE ID_courses = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (ID_courses, Nama_course, Rating_course, Tingkat_kesulitan, Sertifikat_ID_sertifikat, Karyawan_NIK) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function update($data) {
        $stmt = $this->pdo->prepare("UPDATE courses SET Nama_course = ?, Rating_course = ?, Tingkat_kesulitan = ?, Sertifikat_ID_sertifikat = ?, Karyawan_NIK = ? WHERE ID_courses = ?");
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE ID_courses = ?");
        return $stmt->execute([$id]);
    }
}