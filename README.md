# TPFW: juego web

Proyecto práctico final de Dario Salvetti y Matias Beratz. Es una aplicación web en desarrollo que combina PHP, HTML, CSS, JavaScript y SQLite. La idea es ofrecer un juego con cuentas de usuario, monedas, aspectos y un ranking.

## Funcionalidades implementadas

- Registro de usuarios e inicio de sesión desde la página principal.
- Contraseñas almacenadas con `password_hash` y verificadas con `password_verify`.
- Creación automática de las tablas `usuarios`, `skins` y `ranking` al cargar la aplicación.
- Asignación inicial de 100 monedas a las cuentas nuevas.
- Sesión de usuario para acceder al menú y mostrar el nombre y las monedas.
- Cierre de sesión.
- Pantalla de menú con accesos visuales a Jugar, Tienda, Aspectos y Rankings.
- Formulario para cargar skins en la base de datos.
- Prototipo de juego para dos jugadores: la primera barra se mueve con `W` y `S`, y la segunda con `I` y `K`.

## Estado actual

El proyecto sigue en desarrollo. Las opciones de Tienda, Aspectos y Rankings están representadas en el menú, pero todavía no tienen implementada su funcionalidad. El juego es un prototipo independiente; aún no está conectado al inicio de sesión, al sistema de monedas ni al ranking. El formulario de carga de skins también es una herramienta básica de desarrollo.

## Estructura del proyecto

```text
.
├── index.php             # Registro, inicio de sesión e inicialización de tablas
├── conexionDB.php        # Conexión compartida con SQLite
├── cerrarsesion.php      # Cierre de sesión
├── database.db           # Base de datos SQLite
├── menu/
│   ├── menu.php          # Validación de sesión y datos del usuario
│   ├── vistamenu.php     # Interfaz del menú
│   └── crearskin.php     # Formulario de carga de skins
├── html/
│   └── index.html        # Página del prototipo de juego
├── css/                  # Hojas de estilo
├── js/
│   ├── java.js           # Movimiento de las barras del prototipo
│   └── menu.js           # Interacción de las opciones del menú
└── imagenes/             # Recursos gráficos del menú
```

## Ejecución local

Se necesita PHP con las extensiones PDO y PDO SQLite habilitadas.

1. Abrir una terminal en la carpeta del proyecto.
2. Iniciar el servidor de desarrollo:

   ```bash
   php -S localhost:8000
   ```

3. Abrir [http://localhost:8000](http://localhost:8000) en el navegador.

La base de datos `database.db` se encuentra en la carpeta raíz del proyecto. Las tablas se crean automáticamente al visitar `index.php`.
