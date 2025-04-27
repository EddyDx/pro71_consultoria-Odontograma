const botones = document.querySelectorAll('.botonera button');
const zonas = document.querySelectorAll('.zona');

let accionSeleccionada = null;
let colorSeleccionado = null;

// Selección del botón
botones.forEach(boton => {
  boton.addEventListener('click', () => {
    botones.forEach(b => b.classList.remove('activo'));
    boton.classList.add('activo');

    // Detectar acción y color según la clase del botón
    if (boton.classList.contains('fractura')) {
      accionSeleccionada = 'fractura';
      colorSeleccionado = '#d9534f';
    } else if (boton.classList.contains('obturacion')) {
      accionSeleccionada = 'obturacion';
      colorSeleccionado = '#337ab7';
    } else if (boton.classList.contains('extraccion')) {
      accionSeleccionada = 'extraccion';
      colorSeleccionado = '#f0ad4e';
    } else if (boton.classList.contains('a-extraer')) {
      accionSeleccionada = 'a-extraer';
      colorSeleccionado = '#f7c460';
    } else if (boton.classList.contains('puente')) {
      accionSeleccionada = 'puente';
      colorSeleccionado = '#5bc0de';
    } else if (boton.classList.contains('borrar')) {
      accionSeleccionada = 'borrar';
      colorSeleccionado = null;
    } else {
      accionSeleccionada = null;
      colorSeleccionado = null;
    }
  });
});

// Al hacer clic en una zona individual
zonas.forEach(zona => {
  zona.addEventListener('click', () => {
    if (!accionSeleccionada) return;

    if (accionSeleccionada === 'borrar') {
      zona.classList.remove('seleccionada');
      zona.style.backgroundColor = '';
      zona.style.borderColor = '';
    } else {
      zona.classList.add('seleccionada');
      zona.style.backgroundColor = colorSeleccionado;
      zona.style.borderColor = 'black';
    }
  });
});
