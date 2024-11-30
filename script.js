let currentIndex = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

function moveSlide(direction) {
    currentIndex += direction;

    // Asegúrate de que el índice no se salga de los límites
    if (currentIndex < 0) {
        currentIndex = totalSlides - 1;
    } else if (currentIndex >= totalSlides) {
        currentIndex = 0;
    }

    // Desplazar el carrusel
    const offset = -currentIndex * 100; // 100% para el ancho del slide
    document.querySelector('.carrusel-contenido').style.transform = `translateX(${offset}%)`;
}

// Configurar el carrusel automático
let autoSlide = setInterval(() => {
    moveSlide(1);
}, 3000); // Cambiar de slide cada 3 segundos

// Detener el carrusel automático al interactuar con los botones
document.querySelector('.prev').addEventListener('click', () => {
    clearInterval(autoSlide);
    moveSlide(-1);
    autoSlide = setInterval(() => {
        moveSlide(1);
    }, 3000);
});

document.querySelector('.next').addEventListener('click', () => {
    clearInterval(autoSlide);
    moveSlide(1);
    autoSlide = setInterval(() => {
        moveSlide(1);
    }, 3000);
});
