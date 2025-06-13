<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$course_id = $_GET['id'] ?? null;

if (!$course_id) {
    die("ID course tidak ditemukan.");
}

// Cek apakah course ada
$checkCourse = $pdo->prepare("SELECT 1 FROM courses WHERE ID_courses = ?");
$checkCourse->execute([$course_id]);
if (!$checkCourse->fetch()) {
    die("Course tidak ditemukan.");
}

// Cek apakah user sudah join
$cek = $pdo->prepare("SELECT 1 FROM user_course WHERE User_ID = ? AND Courses_ID_Courses = ?");
$cek->execute([$user_id, $course_id]);

if (!$cek->fetch()) {
    // Insert ke user_course
    $insert = $pdo->prepare("INSERT INTO user_course (User_ID, Courses_ID_Courses) VALUES (?, ?)");
    $insert->execute([$user_id, $course_id]);

    // Redirect sukses
    header("Location: course_list.php?status=joined");
    exit();
} else {
    // Redirect info sudah join
    header("Location: course_list.php?status=exists");
    exit();
}
