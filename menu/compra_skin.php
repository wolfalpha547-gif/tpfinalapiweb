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

$usuarioId = (int) $_SESSION['usuario_id'];
$idSkin = filter_input(INPUT_POST, 'id_skin', FILTER_VALIDATE_INT);

if (!$idSkin || $idSkin < 1) {
    $_SESSION['mensaje_tienda'] = "La skin seleccionada no es válida.";
    header("Location: menu.php");
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT precio FROM skins WHERE id = :id");
    $stmt->execute(['id' => $idSkin]);
    $precio = $stmt->fetchColumn();

    if ($precio === false) {
        $pdo->rollBack();
        $_SESSION['mensaje_tienda'] = "La skin seleccionada no existe.";
        header("Location: menu.php");
        exit;
    }

    $precio = (int) $precio;
    if ($precio < 0) {
        error_log("Precio negativo configurado para la skin {$idSkin}.");
        $pdo->rollBack();
        $_SESSION['mensaje_tienda'] = "No se pudo completar la compra. Inténtalo nuevamente.";
        header("Location: menu.php");
        exit;
    }

    $stmt = $pdo->prepare(
        "SELECT 1 FROM armarios WHERE id_usuario = :usuario AND id_skin = :skin"
    );
    $stmt->execute(['usuario' => $usuarioId, 'skin' => $idSkin]);

    if ($stmt->fetchColumn()) {
        $pdo->rollBack();
        $_SESSION['mensaje_tienda'] = "Ya tienes esta skin.";
        header("Location: menu.php");
        exit;
    }

    $stmt = $pdo->prepare(
        "UPDATE usuarios
         SET monedas = monedas - :precio
         WHERE id = :usuario AND monedas >= :saldo_minimo"
    );
    $stmt->execute([
        'precio' => $precio,
        'usuario' => $usuarioId,
        'saldo_minimo' => $precio
    ]);

    if ($stmt->rowCount() !== 1) {
        $pdo->rollBack();
        $_SESSION['mensaje_tienda'] = "No tienes monedas suficientes para comprar esta skin.";
        header("Location: menu.php");
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO armarios (id_usuario, id_skin) VALUES (:usuario, :skin)"
    );
    $stmt->execute(['usuario' => $usuarioId, 'skin' => $idSkin]);

    $pdo->commit();
    $_SESSION['mensaje_tienda'] = "¡Skin comprada con éxito!";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log($e->getMessage());
    $_SESSION['mensaje_tienda'] = "No se pudo completar la compra. Inténtalo nuevamente.";
}

header("Location: menu.php");
exit;
?>
