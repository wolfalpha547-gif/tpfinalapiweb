<?php
session_start();

require_once '../conexionDB.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: menu.php");
    exit;
}

$menuUrl = 'menu.php' . (($_POST['pestana'] ?? '') === 'aspectos' ? '?pestana=aspectos' : '');
$usuarioId = (int) $_SESSION['usuario_id'];
$idSkin = filter_input(INPUT_POST, 'id_skin', FILTER_VALIDATE_INT);

if (!$idSkin || $idSkin < 1) {
    $_SESSION['mensaje_tienda'] = "La skin seleccionada no es válida.";
    header("Location: " . $menuUrl);
    exit;
}

try {
    ensureSkinSelectionSchema($pdo);
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "SELECT skins.tipo
         FROM skins
         JOIN armarios ON armarios.id_skin = skins.id
         WHERE armarios.id_usuario = :usuario AND skins.id = :skin"
    );
    $stmt->execute(['usuario' => $usuarioId, 'skin' => $idSkin]);
    $tipo = $stmt->fetchColumn();

    if ($tipo === false) {
        $pdo->rollBack();
        $_SESSION['mensaje_tienda'] = "No tienes esta skin.";
        header("Location: " . $menuUrl);
        exit;
    }

    $stmt = $pdo->prepare(
        "DELETE FROM skins_seleccionadas WHERE id_usuario = :usuario AND tipo = :tipo"
    );
    $stmt->execute(['usuario' => $usuarioId, 'tipo' => $tipo]);

    $stmt = $pdo->prepare(
        "INSERT INTO skins_seleccionadas (id_usuario, tipo, id_skin)
         VALUES (:usuario, :tipo, :skin)"
    );
    $stmt->execute(['usuario' => $usuarioId, 'tipo' => $tipo, 'skin' => $idSkin]);

    $pdo->commit();
    $_SESSION['mensaje_tienda'] = "Skin seleccionada para usar.";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log($e->getMessage());
    $_SESSION['mensaje_tienda'] = "No se pudo seleccionar la skin. Inténtalo nuevamente.";
}

header("Location: " . $menuUrl);
exit;
?>
