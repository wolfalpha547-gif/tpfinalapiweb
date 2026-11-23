<?php
    session_start();
    require_once "../conexionDB.php";
    
    if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
    }

    ensureSkinSelectionSchema($pdo);

    $usuarioId = $_SESSION['usuario_id'];
    
    $stmt = $pdo->prepare("SELECT usuario_nombre, monedas FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $usuarioId]);
    $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datosUsuario) {
        session_destroy();
        header("Location: ../index.php");
        exit;
    }

    $usuario = $datosUsuario["usuario_nombre"];
    $monedas = $datosUsuario["monedas"];
    $mensaje = $_SESSION['mensaje_tienda'] ?? '';
    unset($_SESSION['mensaje_tienda']);

    $stmt = $pdo->query("SELECT id, nombre_skin, descripcion_skin, precio, tipo FROM skins");
    $skins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id_skin FROM armarios WHERE id_usuario = :id");
    $stmt->execute(['id' => $usuarioId]);
    $skinsCompradas = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

    $stmt = $pdo->prepare(
        "SELECT tipo, id_skin FROM skins_seleccionadas WHERE id_usuario = :id"
    );
    $stmt->execute(['id' => $usuarioId]);
    $skinsSeleccionadas = array_map('intval', $stmt->fetchAll(PDO::FETCH_KEY_PAIR));

    require "vistamenu.php";
?>