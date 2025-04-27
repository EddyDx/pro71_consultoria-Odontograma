// Seleccionar todas las imágenes de dientes
const imagenes = document.querySelectorAll('.img-dientes1, .img-dientes2, .img-dientes3, .img-dientes4');

// Añadir eventos
imagenes.forEach(imagen => {
  imagen.addEventListener('mouseover', () => {
    if (imagen.classList.contains('img-dientes1') || imagen.classList.contains('img-dientes2')) {
      imagen.style.transform = 'translateY(-15px)'; // Subir
    } else if (imagen.classList.contains('img-dientes3') || imagen.classList.contains('img-dientes4')) {
      imagen.style.transform = 'translateY(15px)'; // Bajar
    }
    imagen.style.transition = 'transform 0.3s ease-in-out'; // Suavizar
  });

  // Restaurar al salir
  imagen.addEventListener('mouseout', () => {
    imagen.style.transform = 'translateY(0)';
  });
});
