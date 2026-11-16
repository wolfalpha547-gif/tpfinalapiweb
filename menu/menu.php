<?php
    session_start();
    require_once "../conexionDB.php";
    
    if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
    }

    $usuarioId = $_SESSION['usuario_id'];
    
    $stmt = $pdo->prepare("SELECT usuario_nombre, monedas FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $usuarioId]);
    $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $usuario = $datosUsuario["usuario_nombre"];
    $monedas = $datosUsuario["monedas"];

    require "vistamenu.php";
?>