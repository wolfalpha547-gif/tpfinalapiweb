<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/menu.css">
</head>

<body>
    <div class="usuario">
        <p><?= htmlspecialchars($usuario) ?>  |<img src="../imagenes/dinero.png"> <?= htmlspecialchars($monedas) ?></p>
    </div>
    <div class="esquina">
        <button id="configuraciones"><img src="../imagenes/config.png" alt="configuraciones"></button>
        <button id="salir"><a href="../cerrarsesion.php"><img src="../imagenes/salir.png" alt="salir"></a></button>
    </div>
    <div id="marco">
        <div class="pestaña" name="jugar">JUGAR</div>
        <div class="pestaña" name="tienda">TIENDA</div>
        <div class="pestaña" name="aspectos">ASPECTOS</div>
        <div class="pestaña" name="rankings">RANKINGS</div>
    </div>
    <div class="botones">
        <button id="jugar" class="ventana"><img src="../imagenes/jugar.png" alt="jugar"></button>
        <button id="tienda" class="ventana"><img src="../imagenes/tienda.png" alt="tienda"></button>
        <button id="aspectos" class="ventana"><img src="../imagenes/aspectos.png" alt="aspectos"></button>
        <button id="rankings" class="ventana"><img src="../imagenes/ranking.png" alt="rankings"></button>
    </div>

    <div id="info">
        <p>hola</p>
    </div>
    <script src="../js/menu.js"></script>
</body>

</html>