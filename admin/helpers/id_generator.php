<?php
function generateIDUnique($pdo, $table, $col, $prefix, $length = 4) {
    do {
        $id = $prefix . strtoupper(bin2hex(random_bytes($length)));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $col = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);
    return $id;
}