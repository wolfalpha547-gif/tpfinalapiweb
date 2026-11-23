<?php

$rutaDb = __DIR__ . '/database.db';

try {
    $pdo = new PDO("sqlite:" . $rutaDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión SQLite: " . $e->getMessage());
}

function ensureSkinSelectionSchema(PDO $pdo): void
{
    $columnasUsuarios = $pdo->query("PRAGMA table_info(usuarios)")->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('skin_seleccionada', $columnasUsuarios, true)) {
        $pdo->exec("ALTER TABLE usuarios ADD COLUMN skin_seleccionada INTEGER");
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS skins_seleccionadas (
        id_usuario INTEGER NOT NULL,
        tipo TEXT NOT NULL,
        id_skin INTEGER NOT NULL,
        PRIMARY KEY (id_usuario, tipo),
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
        FOREIGN KEY (id_skin) REFERENCES skins(id)
    )");

    $pdo->exec(
        "INSERT OR IGNORE INTO skins_seleccionadas (id_usuario, tipo, id_skin)
         SELECT usuarios.id, skins.tipo, skins.id
         FROM usuarios
         JOIN skins ON skins.id = usuarios.skin_seleccionada
         JOIN armarios ON armarios.id_usuario = usuarios.id
                     AND armarios.id_skin = skins.id"
    );
}
?>