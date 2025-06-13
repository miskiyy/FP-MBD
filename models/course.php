<?php
class Course {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCourses() {
        $stmt = $this->pdo->query("SELECT * FROM course");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCourse($data) {
        $stmt = $this->pdo->prepare("INSERT INTO course (ID, Nama_Course, Keterangan) VALUES (?, ?, ?)");
        return $stmt->execute($data);
    }

    public function deleteCourse($id) {
        $stmt = $this->pdo->prepare("DELETE FROM course WHERE ID = ?");
        return $stmt->execute([$id]);
    }

    public function updateCourse($data) {
        $stmt = $this->pdo->prepare("UPDATE course SET Nama_Course = ?, Keterangan = ? WHERE ID = ?");
        return $stmt->execute($data);
    }
}
