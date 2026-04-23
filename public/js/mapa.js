// Seleccionamos todos los botones de continuar y regresar
document.querySelectorAll('.btn-continuar').forEach(btn => {
    btn.addEventListener('click', function () {
        const siguientePaso = this.getAttribute('data-siguiente');
        const pasoActual = this.closest('.paso-form');

        let valido = true;
        let mensajeError = '';

        // Obtenemos el número del paso actual
        const numeroPaso = pasoActual.getAttribute('data-paso');

        // VALIDACIÓN SEGÚN EL PASO
        if (numeroPaso === '1') {
            // Paso 1: Descripción
            const descripcion = pasoActual.querySelector('textarea[name="descripcion"]').value.trim();
            if (!descripcion) {
                mensajeError = 'Por favor escribe una descripción del problema.';
                valido = false;
            } else if (descripcion.length < 20) {
                mensajeError = 'La descripción debe tener al menos 20 caracteres.';
                valido = false;
            }

        } else if (numeroPaso === '2') {
            // Paso 2: Tipo de problema
            const tipo = pasoActual.querySelector('select[name="tipo_problema_id"]').value;
            if (!tipo || tipo === '') {
                mensajeError = 'Debes seleccionar un tipo de problema.';
                valido = false;
            }

        } else if (numeroPaso === '3') {
            // Paso 3: Foto
            const inputFoto = document.getElementById('input-foto');
            if (!inputFoto.files || inputFoto.files.length === 0) {
                mensajeError = 'Debes seleccionar una foto como evidencia.';
                valido = false;
            }

        } else if (numeroPaso === '4') {
            // Paso 4: Dirección es OPCIONAL → siempre válido
            // No hacemos nada, siempre puede continuar

        } else if (numeroPaso === '5') {
            // Paso 5: Ubicación en mapa
            const latitud = document.getElementById('latitud').value;
            const longitud = document.getElementById('longitud').value;
            if (!latitud || !longitud) {
                mensajeError = 'Debes hacer clic en el mapa para marcar la ubicación.';
                valido = false;
            }
        }

        // Si NO es válido → mostrar error y detener
        if (!valido) {
            alert(mensajeError);
            return; // No avanza
        }

        // Si es válido → cambiar al siguiente paso
        cambiarPaso(siguientePaso);
    });
});

// Botones "Regresar" (siempre permiten ir atrás, sin validación)
document.querySelectorAll('.btn-regresar').forEach(btn => {
    btn.addEventListener('click', function () {
        const anteriorPaso = this.getAttribute('data-anterior');
        cambiarPaso(anteriorPaso);
    });
});

// Función para cambiar de paso (debes tenerla ya, pero por si acaso)
function cambiarPaso(numeroPaso) {
    // Ocultar todos los pasos
    document.querySelectorAll('.paso-form').forEach(p => {
        p.classList.remove('activo');
    });
    // Mostrar el seleccionado
    document.querySelector(`.paso-form[data-paso="${numeroPaso}"]`).classList.add('activo');

    // Actualizar barra lateral de pasos
    document.querySelectorAll('.paso').forEach(p => {
        p.classList.remove('activo');
    });
    document.querySelector(`.paso[data-paso="${numeroPaso}"]`).classList.add('activo');
}