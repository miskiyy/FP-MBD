-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 04:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserCourses` (IN `p_User_ID` CHAR(6))   BEGIN
  SELECT c.Nama_course, c.Rating_course, c.Tingkat_kesulitan, uc.Status_course
  FROM user_course uc
  JOIN courses c ON uc.Courses_ID_Courses = c.ID_courses
  WHERE uc.User_ID = p_User_ID;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `total_course_user` (`p_user_id` CHAR(6)) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total
    FROM user_course
    WHERE User_ID = p_user_id;
    RETURN total;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `challenge`
--

CREATE TABLE `challenge` (
  `id_challenge` char(6) NOT NULL,
  `nama_challenge` varchar(50) NOT NULL,
  `deskripsi` varchar(200) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `kuota_pemenang` int(11) NOT NULL,
  `hadiah` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge`
--

INSERT INTO `challenge` (`id_challenge`, `nama_challenge`, `deskripsi`, `tanggal_mulai`, `tanggal_berakhir`, `kuota_pemenang`, `hadiah`) VALUES
('CHL001', 'FEBE & Android', 'Buatlah project sesuai dengan course yang dipilih (frontend, backend, atau android) yang berupa prototype', '2023-10-12', '2023-10-19', 10, 'sumsang s30'),
('CHL002', 'Baparekraf Digital Talent Challenge', 'Buatlah produk digital dengan salah satu tema yang sudah ditentukan dan produk bisa diakses publik.', '2023-07-22', '2023-07-29', 5, 'imango 9'),
('CHL003', 'Compose Champion Indonesia', 'Buatlah sebuah aplikasi dengan UI yang kemudian di-migrasi menggunakan Jetpack Compose.', '2023-08-23', '2023-09-23', 13, 'laptop gimang'),
('CHL004', 'Augmented Reality Creator', 'Buatlah teknologi (AR) bertema batik yang dapat dimanfaatkan sebagai media pemasaran produk', '2023-11-18', '2023-11-25', 50, 'sticker anime kesukaan anda');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_user`
--

CREATE TABLE `challenge_user` (
  `challenge_id_challenge` char(6) NOT NULL,
  `User_ID` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge_user`
--

INSERT INTO `challenge_user` (`challenge_id_challenge`, `User_ID`) VALUES
('CHL002', '000004'),
('CHL002', '000003'),
('CHL001', '000001'),
('CHL001', '000003'),
('CHL001', '000005'),
('CHL004', '000001'),
('CHL004', '000004'),
('CHL001', '000004'),
('CHL003', '000002');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `ID_courses` char(6) NOT NULL,
  `Nama_course` varchar(100) NOT NULL,
  `Rating_course` float DEFAULT NULL,
  `Tingkat_kesulitan` float DEFAULT NULL,
  `Sertifikat_ID_sertifikat` char(8) DEFAULT NULL,
  `Karyawan_NIK` char(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`ID_courses`, `Nama_course`, `Rating_course`, `Tingkat_kesulitan`, `Sertifikat_ID_sertifikat`, `Karyawan_NIK`) VALUES
('CRS001', 'Python', 9.7, 7.8, 'SRF001', '1234567890123456'),
('CRS002', 'Data Science', 9.3, 8.6, 'SRF002', '1367890123456245'),
('CRS003', 'Git dan Github', 9.6, 9.5, 'SRF003', '1356789012345624'),
('CRS004', 'HTML', 9.4, 8, 'SRF004', '1345678901234562'),
('CRS005', 'CSS', 9.8, 8.3, 'SRF005', '1456789012345623'),
('CRS006', 'JavaScript', 9.5, 9, 'SRF006', '1567890123456234'),
('CRS007', 'Linux', 9.5, 9.4, 'SRF007', '1367890123456245'),
('CRS008', 'C++', 9.8, 9.7, 'SRF008', '1345678901234562'),
('CRS009', 'C', 9.7, 9.3, 'SRF009', '1456789012345623'),
('CRS010', 'React', 9.5, 8, 'SRF010', '1234567890123456');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `ID_event` char(6) NOT NULL,
  `Nama_Event` varchar(100) NOT NULL,
  `Jenis_Event` varchar(30) NOT NULL,
  `Deskripsi_Event` varchar(200) DEFAULT NULL,
  `Lokasi_Acara` varchar(20) NOT NULL,
  `Kuota_Pendaftaran` int(11) NOT NULL,
  `tanggal_mulai_event` date NOT NULL,
  `tanggal_berakhir_event` date NOT NULL,
  `Sertifikat_ID_Sertifikat` char(8) NOT NULL,
  `Karyawan_NIK` char(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`ID_event`, `Nama_Event`, `Jenis_Event`, `Deskripsi_Event`, `Lokasi_Acara`, `Kuota_Pendaftaran`, `tanggal_mulai_event`, `tanggal_berakhir_event`, `Sertifikat_ID_Sertifikat`, `Karyawan_NIK`) VALUES
('EVX001', 'Cyber Security in Banking', 'Seminar', 'Dalam perkembangan zaman, keamanan dalam dunia banking juga menjadi perhatian sangat penting untuk di perhatikan. Maka dari itu GDSC iSTTS kembali hadir dalam rangka seminar bersama BRI!', 'Zoom', 200, '2023-10-01', '2023-10-11', 'SRF011', '1456789012345623'),
('EVX002', 'Empowering Tech Explorers', 'Seminar', 'Get ready to be inspired and empowered at the GDSC Trilogi University Onboarding! We are thrilled to welcome a special guest who will share invaluable insights and knowledge', 'Zoom', 250, '2023-08-03', '2023-08-09', 'SRF012', '1367890123456245'),
('EVX003', 'Study Jam UI/UX', 'Workshop', 'Study Jam UI/UX merupakan kelompok belajar yang diselenggarakan oleh GDSC Maliki dan komunitas UINUX yang membahas tentang UX Design.', 'Discord', 150, '2023-06-09', '2023-06-16', 'SRF013', '1456789012345623'),
('EVX004', 'Intermediate Python', 'Workshop', 'Kali ini kita akan belajar lebih banyak tentang Python, khususnya OOP, Subprogram, dll. Kita akan mengeksplorasi lebih jauh tentang penggunaan bahasa Python dan cara mengimplementasikannya dalam proye', 'Google Meet', 180, '2023-11-20', '2023-11-28', 'SRF014', '1234567890123456');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `NIK` char(16) NOT NULL,
  `First_Name` varchar(60) NOT NULL,
  `Last_Name` varchar(60) DEFAULT NULL,
  `Email` varchar(30) NOT NULL,
  `Riwayat_Pendidikan` varchar(150) NOT NULL,
  `Pengalaman_Kerja` varchar(150) DEFAULT NULL,
  `Jabatan` varchar(60) NOT NULL,
  `Tanggal_Mulai_Bekerja` date NOT NULL,
  `Tanggal_Berakhir` date NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT 'admin123'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`NIK`, `First_Name`, `Last_Name`, `Email`, `Riwayat_Pendidikan`, `Pengalaman_Kerja`, `Jabatan`, `Tanggal_Mulai_Bekerja`, `Tanggal_Berakhir`, `password`) VALUES
('1234567890123456', 'Harry', 'Potter', 'HarryPotter@gmail.com', 'S-3 Computer Engineering', 'Pernah menjadi Software Engineer', 'Pengembang', '2020-01-01', '2023-12-01', 'admin123'),
('1345678901234562', 'Jefri', 'Nichol', 'JefriNichol@gmail.com', 'S-2 Informatic Engineering', 'Sebelumnya pernah menjadi Backend Developer', 'Ahli Pendidikan', '2020-01-04', '2023-12-04', 'admin123'),
('1356789012345624', 'Haechan', NULL, 'Haechan@gmail.com', 'S-2 Science Data', 'Pernah menjadi Cyber Security di pemerintahan', 'Ahli Pendidikan', '2020-01-03', '2023-12-03', 'admin123'),
('1367890123456245', 'Iqbal', 'Ramadhan', 'IqbalRamadhan@gmail.com', 'S-2 Information System', 'Fresh Graduate', 'Ahli Pendidikan', '2020-01-02', '2023-12-02', 'admin123'),
('1456789012345623', 'Jisoo', NULL, 'Jisoo@gmail.com', 'S-1 Informatic Engineering', 'Fresh Graduate', 'Ahli Pendidikan', '2020-01-05', '2023-12-05', 'admin123'),
('1567890123456234', 'Taylor', 'Swift', 'TaylorSwift@gmail.com', 'S-2 Marketing Analytics', 'Pernah menjadi designer UI/UX', 'Marketing', '2020-01-06', '2023-12-06', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `log_kelulusan`
--

CREATE TABLE `log_kelulusan` (
  `id` int(11) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `kursus_id` varchar(10) DEFAULT NULL,
  `waktu_lulus` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_kelulusan`
--

INSERT INTO `log_kelulusan` (`id`, `user_id`, `kursus_id`, `waktu_lulus`) VALUES
(1, '000003', 'CRS002', '2025-06-08 22:28:21'),
(2, '000004', 'CRS004', '2025-06-08 23:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `log_transaksi`
--

CREATE TABLE `log_transaksi` (
  `id_log` int(11) NOT NULL,
  `id_transaksi` char(6) DEFAULT NULL,
  `user_id` char(6) DEFAULT NULL,
  `waktu_log` timestamp NOT NULL DEFAULT current_timestamp(),
  `aksi` varchar(10) DEFAULT NULL,
  `total_awal_old` decimal(10,2) DEFAULT NULL,
  `total_awal_new` decimal(10,2) DEFAULT NULL,
  `total_akhir_old` decimal(10,2) DEFAULT NULL,
  `total_akhir_new` decimal(10,2) DEFAULT NULL,
  `diskon_old` float DEFAULT NULL,
  `diskon_new` float DEFAULT NULL,
  `status_bayar_old` varchar(30) DEFAULT NULL,
  `status_bayar_new` varchar(30) DEFAULT NULL,
  `metode_bayar_old` varchar(30) DEFAULT NULL,
  `metode_bayar_new` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_transaksi`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_warning`
--

CREATE TABLE `log_warning` (
  `id` int(11) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `jumlah_event` int(11) DEFAULT NULL,
  `waktu_warning` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_warning`
--


-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `ID_paket` char(4) NOT NULL,
  `Nama_paket` varchar(20) NOT NULL,
  `Durasi_paket` int(11) NOT NULL,
  `Harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`ID_paket`, `Nama_paket`, `Durasi_paket`, `Harga`) VALUES
('PKT1', 'Pemula', 1, 100000.00),
('PKT2', 'Jago', 3, 250000.00),
('PKT3', 'Sepuh', 6, 500000.00),
('PKT4', 'Suhu', 12, 1000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `redeem_code`
--

CREATE TABLE `redeem_code` (
  `Kode` varchar(15) NOT NULL,
  `Diskon` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `redeem_code`
--

INSERT INTO `redeem_code` (`Kode`, `Diskon`) VALUES
('MISKIKEREN', 0.65),
('PAYDAY45', 0.45),
('SUKSES20', 0.2),
('THEFIRST', 0.1),
('WINDABIRTHDAY', 0.7);

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE `sertifikat` (
  `ID_Sertifikat` char(8) NOT NULL,
  `Nama_Sertifikat` varchar(100) NOT NULL,
  `sertif_template` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`ID_Sertifikat`, `Nama_Sertifikat`, `sertif_template`) VALUES
('notassig', 'Belum Terassign', 'default_template.png'),
('SRF001', 'Python', 'default_template.png'),
('SRF002', 'Data Science', 'default_template.png'),
('SRF003', 'Git dan GitHub', 'default_template.png'),
('SRF004', 'HTML', 'default_template.png'),
('SRF005', 'CSS', 'default_template.png'),
('SRF006', 'JavaScript', 'default_template.png'),
('SRF007', 'Linux', 'default_template.png'),
('SRF008', 'C++', 'default_template.png'),
('SRF009', 'C', 'default_template.png'),
('SRF010', 'React', 'default_template.png'),
('SRF011', 'Cyber Security in Banking', 'default_template.png'),
('SRF012', 'Empowering Tech Explorers', 'default_template.png'),
('SRF013', 'Study Jam UI/UX', 'default_template.png'),
('SRF014', 'Intermediate Python', 'default_template.png');

-- --------------------------------------------------------

--
-- Table structure for table `sertifuser`
--

CREATE TABLE `sertifuser` (
  `User_ID` char(6) NOT NULL,
  `Sertifikat_ID_Sertifikat` char(6) NOT NULL,
  `tanggal_diberikan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sertifuser`
--

INSERT INTO `sertifuser` (`User_ID`, `Sertifikat_ID_Sertifikat`, `tanggal_diberikan`) VALUES
('000001', 'SRF004', '2025-06-12 15:46:33'),
('000001', 'SRF008', '2025-06-12 15:46:33'),
('000001', 'SRF011', '2025-06-12 15:46:33'),
('000001', 'SRF012', '2025-06-12 15:46:33'),
('000002', 'SRF002', '2025-06-12 15:46:33'),
('000002', 'SRF012', '2025-06-12 15:46:33'),
('000002', 'SRF014', '2025-06-12 15:46:33'),
('000003', 'SRF001', '2025-06-12 15:46:33'),
('000003', 'SRF012', '2025-06-12 15:46:33'),
('000003', 'SRF005', '2025-06-12 15:46:33'),
('000005', 'SRF006', '2025-06-12 15:46:33'),
('000005', 'SRF013', '2025-06-12 15:46:33'),
('000005', 'SRF012', '2025-06-12 15:46:33'),
('000004', 'SRF004', '2025-06-12 15:46:33');

--
-- Triggers `sertifuser`
--
DELIMITER $$
CREATE TRIGGER `trg_set_lulus_dari_sertifikat` AFTER INSERT ON `sertifuser` FOR EACH ROW BEGIN
  DECLARE v_course_id CHAR(6);

  -- Cari apakah sertifikat ini digunakan oleh salah satu course
  SELECT ID_courses
  INTO v_course_id
  FROM courses
  WHERE Sertifikat_ID_sertifikat = NEW.Sertifikat_ID_Sertifikat
  LIMIT 1;

  -- Jika ada course yang pakai sertifikat ini, update status user ke "Lulus"
  IF v_course_id IS NOT NULL THEN
    UPDATE user_course
    SET Status_course = 'Lulus'
    WHERE User_ID = NEW.User_ID
      AND Courses_ID_Courses = v_course_id;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `ID_Transaksi` char(6) NOT NULL,
  `Tanggal_Pemesanan` date NOT NULL,
  `Total_Awal` decimal(10,2) NOT NULL,
  `REDEEM_CODE` varchar(10) DEFAULT NULL,
  `Diskon` float DEFAULT NULL,
  `Total_Akhir` decimal(10,2) NOT NULL,
  `Status_Pembayaran` varchar(30) NOT NULL,
  `Tanggal_Dimulai` date DEFAULT NULL,
  `Tanggal_Berakhir` date DEFAULT NULL,
  `Bukti_Pembayaran` varchar(255) DEFAULT NULL,
  `User_ID` char(6) NOT NULL,
  `Paket_ID_Paket` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `isiTanggalTransaksi` BEFORE UPDATE ON `transaksi` FOR EACH ROW BEGIN
  IF NEW.Status_Pembayaran = 'Lunas' AND OLD.Status_Pembayaran != 'Lunas' THEN
    SET NEW.Tanggal_Dimulai = CURDATE();
    SET NEW.Tanggal_Berakhir = DATE_ADD(CURDATE(), INTERVAL (
      SELECT Durasi_paket FROM paket WHERE ID_paket = NEW.Paket_ID_Paket
    ) MONTH);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_log_transaksi_audit` AFTER UPDATE ON `transaksi` FOR EACH ROW BEGIN
  INSERT INTO log_transaksi (
    id_transaksi, user_id, aksi,
    total_awal_old, total_awal_new,
    total_akhir_old, total_akhir_new,
    diskon_old, diskon_new,
    status_bayar_old, status_bayar_new
  )
  VALUES (
    OLD.ID_Transaksi, OLD.User_ID, 'UPDATE',
    OLD.Total_Awal, NEW.Total_Awal,
    OLD.Total_Akhir, NEW.Total_Akhir,
    OLD.Diskon, NEW.Diskon,
    OLD.Status_Pembayaran, NEW.Status_Pembayaran
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_hitung_total_akhir` BEFORE INSERT ON `transaksi` FOR EACH ROW BEGIN
  IF NEW.REDEEM_CODE IS NOT NULL AND NEW.Diskon IS NOT NULL THEN
    SET NEW.Total_Akhir = NEW.Total_Awal - (NEW.Total_Awal * NEW.Diskon);
  ELSE
    SET NEW.Total_Akhir = NEW.Total_Awal;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` char(6) NOT NULL,
  `First_Name` varchar(60) NOT NULL,
  `Last_Name` varchar(60) DEFAULT NULL,
  `Jenis_kelamin` char(1) NOT NULL,
  `Pekerjaan` varchar(60) NOT NULL,
  `Kota` varchar(60) NOT NULL,
  `Negara` varchar(60) NOT NULL,
  `Nomor_Telepon` varchar(15) NOT NULL,
  `Email` varchar(60) NOT NULL,
  `Tentang_Saya` varchar(150) DEFAULT NULL,
  `Foto_Profil` varchar(255) NOT NULL DEFAULT 'uploads/default.jpeg',
  `Tanggal_Lahir` date NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '123456'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `First_Name`, `Last_Name`, `Jenis_kelamin`, `Pekerjaan`, `Kota`, `Negara`, `Nomor_Telepon`, `Email`, `Tentang_Saya`, `Tanggal_Lahir`, `password`) VALUES
('000001', 'Miskiyah', '', 'P', 'Mahasiswa', 'Surabaya', 'Indonesia', '85712341234', 'miskiyah@gmail.com', 'Saya cita citanya menjadi hokage tp kata mamah egk boleh', '2004-02-02', '123456'),
('000002', 'Winda', ' Nafiqih', 'P', 'Mahasiswa', 'Nganjuk', 'Indonesia', '85712341235', 'WindaNafiqih@gmail.com', NULL, '2004-03-03', '123456'),
('000003', 'Alma', 'Khusnia', 'P', 'Mahasiswa', 'Blitar', 'Indonesia', '85712341236', 'AlmaKhusnia@gmail.com', 'saya mau selesaiin dasprog', '2004-04-04', '123456'),
('000004', 'Zaky', 'Fathurrahman', 'L', 'Mahasiswa', 'Surabaya', 'Indonesia', '85712341237', 'ZakyFathurrahman@gmail.com', NULL, '2004-05-05', '123456'),
('000005', 'Aryaka', 'Leorgi ', 'L', 'Mahasiswa', 'Semarang', 'Indonesia', '85712341238', 'Aryaka@gmail.com', NULL, '2004-06-06', '123456'),
('000006', 'Mariadi', NULL, 'L', 'Mahasiswa', 'Bandung', 'Indonesia', '85712341231', 'mariadi@gmail.com', NULL, '2002-06-06', '123456'),
('000007', 'Fela', NULL, 'P', 'Mahasiswa', 'Bandung', 'Indonesia', '85712341231', 'Fela@its.ac.id', NULL, '2003-07-07', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `user_course`
--

CREATE TABLE `user_course` (
  `User_ID` char(6) NOT NULL,
  `Courses_ID_Courses` char(6) NOT NULL,
  `Status_course` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_course`
--

INSERT INTO `user_course` (`User_ID`, `Courses_ID_Courses`, `Status_course`) VALUES
('000001', 'CRS004', 'Lulus'),
('000001', 'CRS008', 'Lulus'),
('000002', 'CRS002', 'Lulus'),
('000003', 'CRS001', 'Lulus'),
('000003', 'CRS002', 'Lulus'),
('000003', 'CRS005', 'Lulus'),
('000004', 'CRS004', 'Lulus'),
('000004', 'CRS009', 'Belum Lulus'),
('000005', 'CRS006', 'Lulus');

--
-- Triggers `user_course`
--
DELIMITER $$
CREATE TRIGGER `trg_log_kelulusan` AFTER UPDATE ON `user_course` FOR EACH ROW BEGIN
    IF OLD.Status_course <> 'Lulus' AND NEW.Status_course = 'Lulus' THEN
        INSERT INTO log_kelulusan (user_id, kursus_id)
        VALUES (NEW.User_ID, NEW.Courses_ID_Courses);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_event`
--

CREATE TABLE `user_event` (
  `User_ID` char(6) NOT NULL,
  `Event_ID_Event` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_event`
--

INSERT INTO `user_event` (`User_ID`, `Event_ID_Event`) VALUES
('000001', 'EVX001'),
('000001', 'EVX002'),
('000002', 'EVX001'),
('000002', 'EVX002'),
('000002', 'EVX003'),
('000002', 'EVX004'),
('000003', 'EVX002'),
('000005', 'EVX002'),
('000005', 'EVX003'),
('000006', 'EVX001'),
('000007', 'EVX002');

--
-- Triggers `user_event`
--
DELIMITER $$
CREATE TRIGGER `trg_warning_event` AFTER INSERT ON `user_event` FOR EACH ROW BEGIN
    DECLARE total_event INT;

    SELECT COUNT(*) INTO total_event
    FROM user_event
    WHERE User_ID = NEW.User_ID;

    IF total_event >= 4 THEN
        INSERT INTO log_warning (user_id, jumlah_event)
        VALUES (NEW.User_ID, total_event);
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `challenge`
--
ALTER TABLE `challenge`
  ADD PRIMARY KEY (`id_challenge`);

--
-- Indexes for table `challenge_user`
--
ALTER TABLE `challenge_user`
  ADD KEY `idx_challenge_user_user` (`User_ID`),
  ADD KEY `idx_challenge_user_challenge` (`challenge_id_challenge`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`ID_courses`),
  ADD KEY `idx_courses_sertifikat` (`Sertifikat_ID_sertifikat`),
  ADD KEY `idx_courses_karyawan` (`Karyawan_NIK`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`ID_event`),
  ADD KEY `idx_event_sertifikat` (`Sertifikat_ID_Sertifikat`),
  ADD KEY `idx_event_karyawan` (`Karyawan_NIK`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`NIK`),
  ADD KEY `idx_karyawan_email` (`Email`);

--
-- Indexes for table `log_kelulusan`
--
ALTER TABLE `log_kelulusan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_transaksi`
--
ALTER TABLE `log_transaksi`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `log_warning`
--
ALTER TABLE `log_warning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`ID_paket`);

--
-- Indexes for table `redeem_code`
--
ALTER TABLE `redeem_code`
  ADD PRIMARY KEY (`Kode`);

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`ID_Sertifikat`);

--
-- Indexes for table `sertifuser`
--
ALTER TABLE `sertifuser`
  ADD KEY `idx_sertifuser_user` (`User_ID`),
  ADD KEY `idx_sertifuser_sertifikat` (`Sertifikat_ID_Sertifikat`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`ID_Transaksi`),
  ADD KEY `idx_transaksi_user` (`User_ID`),
  ADD KEY `idx_transaksi_paket` (`Paket_ID_Paket`),
  ADD KEY `idx_transaksi_tanggal` (`Tanggal_Pemesanan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_user_email` (`Email`);

--
-- Indexes for table `user_course`
--
ALTER TABLE `user_course`
  ADD PRIMARY KEY (`User_ID`,`Courses_ID_Courses`),
  ADD KEY `idx_user_course_user` (`User_ID`),
  ADD KEY `idx_user_course_course` (`Courses_ID_Courses`);

--
-- Indexes for table `user_event`
--
ALTER TABLE `user_event`
  ADD PRIMARY KEY (`User_ID`,`Event_ID_Event`),
  ADD KEY `idx_user_event_user` (`User_ID`),
  ADD KEY `idx_user_event_event` (`Event_ID_Event`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_kelulusan`
--
ALTER TABLE `log_kelulusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_transaksi`
--
ALTER TABLE `log_transaksi`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_warning`
--
ALTER TABLE `log_warning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `challenge_user`
--
ALTER TABLE `challenge_user`
  ADD CONSTRAINT `challenge_user_ibfk_1` FOREIGN KEY (`challenge_id_challenge`) REFERENCES `challenge` (`id_challenge`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `challenge_user_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`Sertifikat_ID_sertifikat`) REFERENCES `sertifikat` (`ID_Sertifikat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`Karyawan_NIK`) REFERENCES `karyawan` (`NIK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`Sertifikat_ID_Sertifikat`) REFERENCES `sertifikat` (`ID_Sertifikat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`Karyawan_NIK`) REFERENCES `karyawan` (`NIK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sertifuser`
--
ALTER TABLE `sertifuser`
  ADD CONSTRAINT `sertifuser_ibfk_1` FOREIGN KEY (`Sertifikat_ID_Sertifikat`) REFERENCES `sertifikat` (`ID_Sertifikat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sertifuser_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`Paket_ID_Paket`) REFERENCES `paket` (`ID_paket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_course`
--
ALTER TABLE `user_course`
  ADD CONSTRAINT `user_course_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_ibfk_2` FOREIGN KEY (`Courses_ID_Courses`) REFERENCES `courses` (`ID_courses`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_event`
--
ALTER TABLE `user_event`
  ADD CONSTRAINT `user_event_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_event_ibfk_2` FOREIGN KEY (`Event_ID_Event`) REFERENCES `event` (`ID_event`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
