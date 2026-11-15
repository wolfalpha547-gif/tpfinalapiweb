<?php
session_start();

require_once '../conexionDB.php';
$error = 'hubo un error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_skin = trim($_POST['nombre_skin'] ?? '');
    $descripcion_skin = $_POST['descripcion_skin'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $accion = $_POST['accion'] ?? '';

        if ($accion === 'registrar') {
            try {
                               
                $stmt = $pdo->prepare("INSERT INTO skins (nombre_skin, descripcion_skin, precio, tipo) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nombre_skin, $descripcion_skin, $precio, $tipo]);
                
                $mensaje = "¡Skin cargada con éxito!.";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'UNIQUE') !== false || strpos($e->getMessage(), 'constraint failed') !== false) {
                    $mensaje = "La skin ya existe.";
                } else {
                    $mensaje = "Error al registrarse: " . $e->getMessage();
                }
            }
        }
        }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div id="login">
        <form class="centrar" method="POST" action="">
            <br>
            <?php if (!empty($mensaje)): ?>
                <div class="alert">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <label for="nombre_skin">nombre skin</label>
            <br>
            <input type="text" name="nombre_skin" id="nombre_skin" >
            <br><br>

            <label for="descripcion_skin">Descripción</label>
            <br>
            <input type="text" name="descripcion_skin" id="descripcion_skin" >
            <br><br>

            <label for="precio">Precio</label>
            <br>
            <input type="number" name="precio" id="precio" >
            <br><br>

            <label for="tipo">Tipo</label>
            <br>
            <input type="text" name="tipo" id="tipo" >
            <br><br>
            <br>
            <br><br>
            <button type="submit" name="accion" value="registrar" id="registrar_skin">Crear cuenta</button>
        </form>
    </div>
    <script src="js/java.js"></script>
</body>
</html>