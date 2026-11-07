<?php
$dbFile = __DIR__ . '/database.db';

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_nombre TEXT NOT NULL UNIQUE,
        contrasena TEXT NOT NULL,
        monedas INTEGER DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS skins (
        skin_id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre_skin TEXT NOT NULL,
        descripcion_skin TEXT,
        precio INTEGER NOT NULL,
        tipo TEXT NOT NULL
    )");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>