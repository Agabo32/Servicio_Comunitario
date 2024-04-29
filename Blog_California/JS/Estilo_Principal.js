window.onscroll = function() {
    var scroll = document.documentElement.scrollTop;
    var Encabezado = document.getElementById("Encabezado");

    if (scroll >  25) {
        Encabezado.classList.add('nav_mod');
    } else if (scroll <  25) {
        Encabezado.classList.remove('nav_mod');
    }

    resaltarEnlaceActual();
}

const enlaces = Array.from(document.querySelectorAll('.Menu ul li a'));

enlaces.forEach(enlace => {
    enlace.addEventListener('click', () => {
        quitarResaltado(enlaces);
        enlace.classList.add('resaltado');
    });
});

function quitarResaltado(enlaces) {
    enlaces.forEach(enl => enl.classList.remove('resaltado'));
}

function resaltarEnlaceActual() {
    const alturaVentana = window.innerHeight;

    enlaces.forEach(enlace => {
        const seccion = document.querySelector(enlace.getAttribute('href'));
        const posicion = seccion.offsetTop;

        if (posicion <= window.scrollY + alturaVentana /  2) {
            resaltarEnlace(enlace, enlaces);
        }
    });
}

function resaltarEnlace(enlace, enlaces) {
    enlaces.forEach(enl => enl.classList.remove('resaltado'));
    enlace.classList.add('resaltado');
}




