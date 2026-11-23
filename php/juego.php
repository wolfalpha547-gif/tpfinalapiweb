<?php
session_start();
require_once '../conexionDB.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../menu/menu.php?pestana=jugar");
    exit;
}

$usuarioId = (int) $_SESSION['usuario_id'];
$usuarioIdRecibido = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
$usuarioRecibido = trim($_POST['usuario'] ?? '');

if ($usuarioIdRecibido !== false && $usuarioIdRecibido !== $usuarioId) {
    http_response_code(403);
    exit('El usuario recibido no coincide con la sesión.');
}

$stmt = $pdo->prepare("SELECT usuario_nombre FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $usuarioId]);
$usuario = $stmt->fetchColumn();

if ($usuario === false) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

if ($usuarioRecibido !== '' && $usuarioRecibido !== $usuario) {
    http_response_code(403);
    exit('El nombre de usuario recibido no coincide con la sesión.');
}

$skinsRecibidas = is_array($_POST['skins'] ?? null) ? $_POST['skins'] : [];
$skinsPorDefecto = [
    'jugador' => 1,
    'pelota' => 4,
    'IA' => 7
];

ensureSkinSelectionSchema($pdo);

$stmt = $pdo->prepare(
    "SELECT tipo, id_skin
     FROM skins_seleccionadas
     WHERE id_usuario = :usuario"
);
$stmt->execute(['usuario' => $usuarioId]);
$seleccionesGuardadas = array_map('intval', $stmt->fetchAll(PDO::FETCH_KEY_PAIR));

$aspectos = [];
foreach ($skinsPorDefecto as $tipo => $idPorDefecto) {
    $idRecibido = filter_var($skinsRecibidas[$tipo] ?? null, FILTER_VALIDATE_INT);
    $idSkin = $seleccionesGuardadas[$tipo] ?? ($idRecibido ?: $idPorDefecto);

    $stmt = $pdo->prepare(
        "SELECT skins.nombre_skin
         FROM skins
         JOIN armarios ON armarios.id_skin = skins.id
         WHERE skins.id = :skin
           AND skins.tipo = :tipo
           AND armarios.id_usuario = :usuario"
    );
    $stmt->execute([
        'skin' => $idSkin,
        'tipo' => $tipo,
        'usuario' => $usuarioId
    ]);
    $nombreSkin = $stmt->fetchColumn();

    if ($nombreSkin === false) {
        $stmt = $pdo->prepare(
            "SELECT nombre_skin FROM skins WHERE id = :skin AND tipo = :tipo"
        );
        $stmt->execute(['skin' => $idPorDefecto, 'tipo' => $tipo]);
        $nombreSkin = $stmt->fetchColumn();
    }

    $aspectos[$tipo] = $nombreSkin ?: $skinsPorDefecto[$tipo];
}

$aspectosJson = json_encode(
    $aspectos,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<canvas width="750" height="585" id="game"></canvas>
<button id="fin"><a href="../menu/menu.php">VOLVER</a></button>
<script>
    window.usuarioJuego = <?= json_encode($usuario, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    window.aspectosJuego = <?= $aspectosJson ?: '{}' ?>;
</script>
<script src="../js/java.js"></script>
</body>
</html>