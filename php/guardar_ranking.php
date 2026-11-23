<?php
session_start();
require_once '../conexionDB.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesión no válida']);
    exit;
}

$datos = json_decode(file_get_contents('php://input'), true);
$puntuacion = filter_var($datos['puntuacion'] ?? null, FILTER_VALIDATE_INT);

if ($puntuacion === false || $puntuacion < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Puntuación no válida']);
    exit;
}

$usuarioId = (int) $_SESSION['usuario_id'];

try {
    $stmt = $pdo->prepare("SELECT usuario_nombre FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $usuarioId]);
    $nombreUsuario = $stmt->fetchColumn();

    if ($nombreUsuario === false) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
        exit;
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS ranking (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre_usuario TEXT NOT NULL,
            puntuacion INTEGER NOT NULL,
            fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $stmt = $pdo->prepare(
        "INSERT INTO ranking (nombre_usuario, puntuacion)
         VALUES (:nombre, :puntuacion)"
    );
    $stmt->execute([
        'nombre' => $nombreUsuario,
        'puntuacion' => $puntuacion
    ]);

    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo guardar el ranking']);
}
