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
        <div class="pestaña" name="jugar">
            <form method="POST" action="url juego.php">
                <?php foreach ($skinsSeleccionadas as $tipo => $idSkin): ?>
                    <input type="hidden" name="skins[<?= htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8') ?>]" value="<?= (int) $idSkin ?>">
                <?php endforeach; ?>
                <button type="submit">Jugar</button>
            </form>
        </div>
        <div class="pestaña" name="tienda">
            <?php foreach ($skins as $skin): ?>
                <div class="objeto" data-descripcion="<?= htmlspecialchars($skin['descripcion_skin'], ENT_QUOTES, 'UTF-8') ?>">
                    <img src="../imagenes/skins/<?= htmlspecialchars($skin['nombre_skin']) ?>.png">
                    <?php if (in_array((int) $skin['id'], $skinsCompradas, true)): ?>
                        <button type="button" disabled>Comprada</button>
                    <?php else: ?>
                        <form method="POST" action="compra_skin.php">
                            <input type="hidden" name="id_skin" value="<?= (int) $skin['id'] ?>">
                            <input type="hidden" name="pestana" value="tienda">
                            <button type="submit"><img src="../imagenes/dinero.png"> - <?= htmlspecialchars($skin['precio']) ?></button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pestaña" name="aspectos">
            <?php $hayAspectos = false; ?>
            <?php foreach ($skins as $skin): ?>
                <?php if (in_array((int) $skin['id'], $skinsCompradas, true)): ?>
                    <?php $hayAspectos = true; ?>
                    <div class="objeto">
                        <img src="../imagenes/skins/<?= htmlspecialchars($skin['nombre_skin']) ?>.png">
                        <?php if (($skinsSeleccionadas[$skin['tipo']] ?? null) === (int) $skin['id']): ?>
                            <button type="button" disabled>Seleccionada</button>
                        <?php else: ?>
                            <form method="POST" action="seleccionar_skin.php">
                                <input type="hidden" name="id_skin" value="<?= (int) $skin['id'] ?>">
                                <input type="hidden" name="pestana" value="aspectos">
                                <button type="submit">Seleccionar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
        </div>
        <div class="pestaña" name="rankings">RANKINGS</div>
    </div>
    <div class="botones">
        <button id="jugar" class="ventana"><img src="../imagenes/jugar.png" alt="jugar"></button>
        <button id="tienda" class="ventana"><img src="../imagenes/tienda.png" alt="tienda"></button>
        <button id="aspectos" class="ventana"><img src="../imagenes/aspectos.png" alt="aspectos"></button>
        <button id="rankings" class="ventana"><img src="../imagenes/ranking.png" alt="rankings"></button>
    </div>

    <div id="info">
        <p role="status"><?= $mensaje !== '' ? htmlspecialchars($mensaje) : '' ?></p>
    </div>
    <script src="../js/menu.js"></script>
</body>

</html>
