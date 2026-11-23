
let ventana = document.getElementById("marco")
let botones = document.querySelectorAll(".ventana")
let pestaña = document.querySelectorAll(".pestaña")
let botonconfig = document.getElementById("configuraciones")
let config = document.querySelector(".config")
let cerrarconfig = document.querySelector(".cerrar")

config.style.display = "none";

botonconfig.addEventListener('click', () =>{
    config.style.display = "";
})

cerrarconfig.addEventListener('click', () =>{
    config.style.display = "none";
})

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

window.addEventListener("DOMContentLoaded", () => {
    const navEntry = performance.getEntriesByType("navigation")[0];
    const esRecarga = navEntry && navEntry.type === "reload";

    const pestanaUrl = new URLSearchParams(window.location.search).get("pestana");
    const pestanasValidas = ["jugar", "tienda", "aspectos", "rankings"];

    let pestanaDestino = "jugar";

    if (!esRecarga && pestanasValidas.includes(pestanaUrl)) {
        pestanaDestino = pestanaUrl;
    }

    document.querySelector(`#${pestanaDestino}`).click();
});

const info = document.querySelector("#info [role='status']")
const mensajeOriginal = info.textContent

document.querySelectorAll('.pestaña[name="tienda"] .objeto').forEach(objeto => {
    objeto.addEventListener("mouseenter", () => {
        info.textContent = objeto.dataset.descripcion || "Esta skin no tiene descripción."
    })

    objeto.addEventListener("mouseleave", () => {
        info.textContent = mensajeOriginal
    })
})