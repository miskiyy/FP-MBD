<?php
class SertifUser {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ambil semua data sertifuser (bisa difilter tipe event/course jika perlu)
    public function getAll($tipe = 'event') {
        if ($tipe == 'course') {
            $query = "
                SELECT 
                    u.ID AS User_ID,
                    CONCAT(COALESCE(u.First_Name, ''), ' ', COALESCE(u.Last_Name, '')) AS Nama,
                    c.Nama_course,
                    s.ID_Sertifikat,
                    s.Nama_Sertifikat,
                    su.tanggal_diberikan
                FROM user u
                JOIN user_course uc ON uc.User_ID = u.ID
                JOIN courses c ON c.ID_courses = uc.Courses_ID_Courses
                JOIN sertifikat s ON s.ID_Sertifikat = c.Sertifikat_ID_sertifikat
                LEFT JOIN sertifuser su 
                    ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
                ORDER BY u.ID
            ";
        } else {
            $query = "
                SELECT 
                    u.ID AS User_ID,
                    CONCAT(COALESCE(u.First_Name, ''), ' ', COALESCE(u.Last_Name, '')) AS Nama,
                    e.Nama_Event,
                    s.ID_Sertifikat,
                    s.Nama_Sertifikat,
                    su.tanggal_diberikan
                FROM user u
                JOIN user_event ue ON ue.User_ID = u.ID
                JOIN event e ON e.ID_event = ue.Event_ID_Event
                JOIN sertifikat s ON s.ID_Sertifikat = e.Sertifikat_ID_Sertifikat
                LEFT JOIN sertifuser su 
                    ON su.User_ID = u.ID AND su.Sertifikat_ID_Sertifikat = s.ID_Sertifikat
                ORDER BY u.ID
            ";
        }
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cek apakah user sudah punya sertifikat tertentu
    public function exists($user_id, $sertif_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM sertifuser WHERE User_ID = ? AND Sertifikat_ID_Sertifikat = ?");
        $stmt->execute([$user_id, $sertif_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Assign sertifikat ke user
    public function add($user_id, $sertif_id) {
        $stmt = $this->pdo->prepare("INSERT INTO sertifuser (User_ID, Sertifikat_ID_Sertifikat) VALUES (?, ?)");
        return $stmt->execute([$user_id, $sertif_id]);
    }

    // Cabut sertifikat dari user
    public function delete($user_id, $sertif_id) {
        $stmt = $this->pdo->prepare("DELETE FROM sertifuser WHERE User_ID = ? AND Sertifikat_ID_Sertifikat = ?");
        return $stmt->execute([$user_id, $sertif_id]);
    }

    // (Opsional) 
    public function updateTanggal($user_id, $sertif_id, $tanggal) {
        $stmt = $this->pdo->prepare("UPDATE sertifuser SET tanggal_diberikan = ? WHERE User_ID = ? AND Sertifikat_ID_Sertifikat = ?");
        return $stmt->execute([$tanggal, $user_id, $sertif_id]);
    }
}