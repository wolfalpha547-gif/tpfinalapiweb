
let ventana = document.getElementById("marco")
let botones = document.querySelectorAll(".ventana")
let pestaña = document.querySelectorAll(".pestaña")

botones.forEach(boton => {
    boton.addEventListener('click', (evento) => {

        // A. Quitamos la clase 'activo' del botón que la tenía antes
        botones.forEach(b => b.classList.remove('activo'));

        // B. Añadimos la clase 'activo' solo al botón clickeado
        const botonSeleccionado = evento.currentTarget;
        botonSeleccionado.classList.add('activo');

        // C. Obtenemos el ID del elemento clickeado
        const idActivo = botonSeleccionado.id;

        pestaña.forEach(p => {
            if (idActivo === p.getAttribute('name')) {
                p.style.display = '';
            } else {
                p.style.display = 'none';
            }
        });
    });
});

let inicio = document.querySelector("#jugar")
inicio.click()