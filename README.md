# tpfinalapiweb
Grupo : Salvetti Dario y Beratz Matias.
#Descripción

Este trabajo es el practico final que engloba html php css y js  con sqlite.
Estructura: 
(Los nombres estan sujetos a modificacion)
    index.php
        inicio donde se crean las tablas y logea en el sistema  
        dentro del try se crean las tablas usuarios skins y ranking
        el metodo donde se recibe la peticion POST, tiene dos caminos siempre y cuando no este vacio el usuario  y/o contraseña,
        estos caminos son crear un nuevo usuario al elegir registrar o iniciar sesion si el usuario ingresado es valido.
    conexionDB.php
        este archivo funciona para inicializar la base de datos y facilitar la conexion para otros archivos php ademas de evitar la repeticion de codigo aplicando dry
    cerrarsesion.php
        este archivo es utilizado para cerrar las sesiones de los usuarios logeados y redirigirnos a la pagina principal para poder volver a ingresar 
        el if que se encuentra aqui impide que otra persona pueda entrar a la cuenta de un usuario que ya ah cerrado sesion si oprime regresar a la pagina anterior.
    menu.php
        el if redirige a la pagina principal en caso de que el usuario este vacio
        usando el id del usuario se obtiene su nombre y la cantidad de monedas que tiene,
        a futuro tambien traera las skins compradas con dichas monedas.
        finalmente se usa requiere "vistamenu.php" que es donde se hace el html de la pagina.
    menu.js
        es donde se encuentra el javascript del menu.
    java.js
        aqui se esta desarrollando el juego que ejecutara nuestra pagina.
    index.html 
        proximamente sera el juego ejecutado y se convertira en un php.
