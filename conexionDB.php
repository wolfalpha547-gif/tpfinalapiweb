<?php

$rutaDb = __DIR__ . '/database.db';

try {
    $pdo = new PDO("sqlite:" . $rutaDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión SQLite: " . $e->getMessage());
}
?>