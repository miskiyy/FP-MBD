<?php
require_once '../../models/event.php';
require_once '../../config/database.php';
session_start();

$id = $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST["ID_event"] : $_GET["id"];
$eventModel = new Event($pdo);
if ($eventModel->delete($id)) {
    header("Location: manage_event.php?success=1");
} else {
    header("Location: manage_event.php?error=1");
}
?>
