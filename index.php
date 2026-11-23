<?php
session_start();

require_once 'conexionDB.php';
$error = 'hubo un error';

try {
    $pdo->exec("PRAGMA foreign_keys = ON;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_nombre TEXT NOT NULL UNIQUE,
        contrasena TEXT NOT NULL,
        monedas INTEGER DEFAULT 0,
        skin_seleccionada INTEGER
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS skins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre_skin TEXT NOT NULL,
        descripcion_skin TEXT,
        precio INTEGER NOT NULL,
        tipo TEXT NOT NULL
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS ranking (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre_usuario TEXT NOT NULL,
        puntuacion INTEGER NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS armarios(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_usuario INTEGER NOT NULL,
        id_skin INTEGER NOT NULL,
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
        FOREIGN KEY (id_skin) REFERENCES skins(id)
        )");

    ensureSkinSelectionSchema($pdo);
    
} catch (PDOException $e) {
    $mensaje = "Error de base de datos: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $accion = $_POST['accion'] ?? '';

    if (empty($usuario) || empty($contrasena)) {
        $mensaje = "Por favor coloque nombre de usuario o contraseña.";
    } else {
        if ($accion === 'registrar') {
            try {
                $pdo->beginTransaction();

                $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO usuarios (usuario_nombre, contrasena, monedas) VALUES (?, ?, 100)");
                $stmt->execute([$usuario, $hashContrasena]);

                $nuevoUsuarioId = $pdo->lastInsertId();

                $skinsPorDefecto = [1, 4, 7];
                $stmtArmario = $pdo->prepare("INSERT INTO armarios (id_usuario, id_skin) VALUES (?, ?)");
        
                foreach ($skinsPorDefecto as $skinId) {
                    $stmtArmario->execute([$nuevoUsuarioId, $skinId]);
                }

                $pdo->commit();
                
                $mensaje = "¡Cuenta creada con éxito! Ahora puedes iniciar sesión.";
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                if (strpos($e->getMessage(), 'UNIQUE') !== false || strpos($e->getMessage(), 'constraint failed') !== false) {
                    $mensaje = "El nombre de usuario ya está en uso.";
                } else {
                    $mensaje = "Error al registrarse: " . $e->getMessage();
                }
            }
        }
        elseif ($accion === 'login') {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario_nombre = ?");
            $stmt->execute([$usuario]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($contrasena, $user['contrasena'])) {
                $_SESSION['usuario_id'] = $user['id'];
                header("Location: menu/menu.php");
                exit();
            } else {
                $mensaje = "Usuario o contraseña incorrectos.";
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
            <h1 class="text text-center">TITULO JUEGO</h1>
            <h2 class="text text-center">subtitulo</h2>
            <br>
            <?php if (!empty($mensaje)): ?>
                <div class="alert">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <label for="usuario">Nombre de usuario</label>
            <br>
            <input type="text" name="usuario" id="usuario" >
            <br><br>

            <label for="contrasena">Contraseña</label>
            <br>
            <input type="password" name="contrasena" id="contrasena" >
            <br><br>
            <button type="submit" name="accion" value="login" id="iniciar-sesion">Iniciar sesión</button>
            <button type="submit" name="accion" value="registrar" id="registrarse">Crear cuenta</button>
        </form>
    </div>
    <script src="js/java.js"></script>
</body>
</html>