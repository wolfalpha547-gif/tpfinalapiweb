let ventana = document.querySelectorAll(".ventana")
let boton = document.querySelectorAll(".pestaña")

boton.addEventListener("click", (evento) => {
    if (ventan.id == boton.textContent){
        ventana.style.display = "none"
    }else{
        ventana.style.display = ""
    }
})