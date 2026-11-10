const barra = document.querySelector('.barra');
let position = 0;
window.addEventListener('keydown', (event) => {
    if (event.key === 'w') {
        position -= 10;
    } else if (event.key === 's') {
        position += 10;
    }
    if (position < 0 || position > window.innerHeight - barra.offsetHeight) {
        position = Math.max(0, Math.min(position, window.innerHeight - barra.offsetHeight));
    }
    barra.style.transform = `translateY(${position}px)`;
});

const barraia = document.querySelector('.barraia');
let positionia = 0;
window.addEventListener('keydown', (event) => {
    if (event.key === 'i') {
        positionia -= 10;
    } else if (event.key === 'k') {
        positionia += 10;
    }
    if (positionia < 0 || positionia > window.innerHeight - barraia.offsetHeight) {
        positionia = Math.max(0, Math.min(positionia, window.innerHeight - barraia.offsetHeight));
    }
    barraia.style.transform = `translateY(${positionia}px)`;
});