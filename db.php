<?php
// Shared database connection. Update credentials for your setup (XAMPP defaults shown).
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=inventory_db;charset=utf8mb4', 'root', '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function e(string $s): string {           // escape output
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
