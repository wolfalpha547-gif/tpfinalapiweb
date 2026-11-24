# TPFInal API Web

## Integrantes

- Salvetti Dario
- Beratz Matias

## Descripcion

Trabajo practico final desarrollado con HTML, CSS, JavaScript, PHP y SQLite. El sitio incluye un sistema de usuarios, una tienda de aspectos, una seleccion de skins y un ranking de partidas.

## Funcionalidades

- Registro e inicio de sesion con contrasenas cifradas.
- Gestion de sesiones y cierre de sesion seguro.
- Monedas asignadas a cada usuario.
- Compra y desbloqueo de skins.
- Seleccion independiente de aspecto para el jugador, la pelota y la IA.
- Juego Pong contra una IA.
- Guardado de puntuaciones asociado al usuario conectado.
- Ranking con los mejores resultados registrados.
- Adaptacion de los aspectos seleccionados dentro de las partidas.

## Tecnologias utilizadas

- HTML5
- CSS3
- JavaScript
- PHP
- SQLite
- Bootstrap 5
- Canvas API

## Requisitos

- PHP con soporte para PDO SQLite.
- Un servidor web local compatible con PHP, como Apache o XAMPP.
- Un navegador moderno.

## Ejecucion

1. Colocar el proyecto dentro de la carpeta publica del servidor web.
2. Iniciar Apache y asegurarse de tener habilitada la extension de SQLite para PHP.
3. Abrir `index.php` desde el navegador.
4. Crear una cuenta o iniciar sesion.

La base de datos se crea y conecta desde `database.db` mediante `conexionDB.php`. Las tablas necesarias se inicializan al entrar a la aplicacion.

## Uso

Desde el menu se puede acceder a:

- **Jugar:** iniciar una partida Pong.
- **Tienda:** comprar nuevos aspectos con las monedas disponibles.
- **Aspectos:** elegir las skins compradas para el jugador, la pelota y la IA.
- **Ranking:** consultar las mejores puntuaciones.

Al finalizar una partida, la puntuacion del usuario se guarda en SQLite.

## Estructura del proyecto

- `index.php`: inicio de sesion, registro y creacion inicial de tablas.
- `conexionDB.php`: conexion con SQLite y aseguramiento del esquema de skins seleccionadas.
- `cerrarsesion.php`: cierre de sesion.
- `menu/menu.php`: carga de datos del usuario, tienda y ranking.
- `menu/vistamenu.php`: estructura visual del menu.
- `menu/compra_skin.php`: compra de aspectos.
- `menu/seleccionar_skin.php`: seleccion de aspectos.
- `php/juego.php`: validacion de sesion y carga del juego con los aspectos configurados.
- `php/guardar_ranking.php`: guardado de puntuaciones.
- `js/java.js`: logica principal del juego.
- `js/menu.js`: interacciones del menu.
- `css/`: estilos de inicio de sesion, menu y juego.
- `imagenes/`: iconos y aspectos utilizados por la interfaz y el juego.
- `database.db`: base de datos SQLite.

## Nota sobre el juego

El apartado de juego fue obtenido y adaptado a partir de una implementacion creada por otra persona. No fue desarrollado por nosotros desde cero. Modificamos su uso para integrarlo con la sesion del usuario, los aspectos seleccionados, el guardado de puntuaciones y el sistema de ranking del proyecto.

## Otros archivos

El archivo `apuntes.txt` contiene enlaces usados como material de estudio y referencia durante el desarrollo. No corresponde a una parte funcional de la aplicacion.
