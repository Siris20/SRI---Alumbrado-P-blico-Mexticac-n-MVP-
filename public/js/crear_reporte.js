document.addEventListener('DOMContentLoaded', function () {
    const btnNuevo = document.getElementById('btn-nuevo-reporte');
    const listaReportes = document.getElementById('lista-reportes');
    const formulario = document.getElementById('formulario-reporte');
    const pasosLateral = document.querySelectorAll('#pasos-lateral .paso');
    const pasosForm = document.querySelectorAll('.paso-form');
    const form = document.getElementById('form-nuevo-reporte');

    let mapa = null;
    let marcador = null;

    // =================== MOSTRAR FORMULARIO ===================
    btnNuevo.addEventListener('click', function (e) {
        e.preventDefault();
        listaReportes.style.display = 'none';
        formulario.style.display = 'block';
        cambiarPaso(1);
    });

    // =================== FUNCIÓN PARA MOSTRAR ERROR TEMPORAL ===================
    function mostrarErrorTemporal(mensaje) {
        // Eliminar error anterior si existe
        const errorAnterior = document.querySelector('.error-paso');
        if (errorAnterior) errorAnterior.remove();

        const div = document.createElement('div');
        div.className = 'error-paso';
        div.textContent = mensaje;
        div.style.color = '#d32f2f';
        div.style.fontWeight = 'bold';
        div.style.fontSize = '14px';
        div.style.marginTop = '15px';
        div.style.textAlign = 'center';
        div.style.background = '#ffebee';
        div.style.padding = '10px';
        div.style.borderRadius = '8px';
        div.style.border = '1px solid #ffcdd2';

        // Insertar antes de los botones del paso actual
        const botonesContainer = document.querySelector('.paso-form.activo .botones-form');
        botonesContainer.prepend(div);

        // Desaparece automáticamente después de 6 segundos
        setTimeout(() => {
            if (div && div.parentElement) div.remove();
        }, 6000);
    }

    // =================== BOTONES "CONTINUAR" CON VALIDACIÓN ===================
    document.querySelectorAll('.btn-continuar').forEach(btn => {
        btn.addEventListener('click', function () {
            const siguiente = this.getAttribute('data-siguiente');
            const pasoActual = this.closest('.paso-form');
            const numPaso = pasoActual.getAttribute('data-paso');

            let valido = true;
            let mensaje = '';

            switch (numPaso) {
                case '1': // Descripción
                    const descripcion = document.querySelector('textarea[name="descripcion"]').value.trim();
                    if (!descripcion) {
                        mensaje = 'Por favor, escribe una descripción del problema.';
                        valido = false;
                    } else if (descripcion.length < 20) {
                        mensaje = 'La descripción debe tener al menos 20 caracteres.';
                        valido = false;
                    }
                    break;

                case '2': // Tipo de problema
                    const tipo = document.querySelector('select[name="tipo_problema_id"]').value;
                    if (!tipo) {
                        mensaje = 'Debes seleccionar un tipo de problema.';
                        valido = false;
                    }
                    break;

                case '3': // Foto
                    const fotoInput = document.getElementById('input-foto');
                    if (!fotoInput.files || fotoInput.files.length === 0) {
                        mensaje = 'Debes seleccionar una foto como evidencia.';
                        valido = false;
                    }
                    break;

                case '4': // Dirección → opcional, siempre válido
                    valido = true;
                    break;

                default:
                    valido = true;
            }

            if (!valido) {
                mostrarErrorTemporal(mensaje);
                return; // No avanza
            }

            // Avanzar al siguiente paso
            cambiarPaso(parseInt(siguiente));
        });
    });

    // =================== BOTONES "REGRESAR" (sin validación) ===================
    document.querySelectorAll('.btn-regresar').forEach(btn => {
        btn.addEventListener('click', function () {
            const anterior = this.getAttribute('data-anterior');
            cambiarPaso(parseInt(anterior));
        });
    });

    // =================== CAMBIAR DE PASO ===================
    function cambiarPaso(num) {
        // Ocultar todos los pasos del formulario
        pasosForm.forEach(p => p.classList.remove('activo'));
        document.querySelector(`.paso-form[data-paso="${num}"]`).classList.add('activo');

        // Actualizar barra lateral
        pasosLateral.forEach(p => p.classList.remove('activo'));
        document.querySelector(`#pasos-lateral .paso[data-paso="${num}"]`).classList.add('activo');

        // Inicializar mapa solo la primera vez en el paso 5
        if (num === 5 && !mapa) {
            inicializarMapa();
        }
    }

    // =================== INICIALIZAR MAPA (sin cambios) ===================
    function inicializarMapa() {
        const centroMexticacan = [21.2653, -102.7778];
        const bounds = L.latLngBounds(
            [21.23, -102.82],
            [21.30, -102.73]
        );

        mapa = L.map('mapa', {
            center: centroMexticacan,
            zoom: 14,
            minZoom: 13,
            maxZoom: 18,
            maxBounds: bounds,
            maxBoundsViscosity: 1.0
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);

        const infoPopup = L.popup()
            .setLatLng(centroMexticacan)
            .setContent('<strong>Haz clic dentro de Mexticacán para marcar la ubicación del problema</strong>')
            .openOn(mapa);

        mapa.on('click', function(e) {
            if (!bounds.contains(e.latlng)) {
                mostrarErrorTemporal('Por favor, marca la ubicación DENTRO del municipio de Mexticacán.');
                return;
            }

            if (infoPopup) mapa.closePopup();
            if (marcador) marcador.remove();

            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            marcador = L.marker(e.latlng)
                .addTo(mapa)
                .bindPopup('Ubicación seleccionada')
                .openPopup();

            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
        });

        mapa.on('drag', function() {
            mapa.panInsideBounds(bounds, { animate: true });
        });
    }

    // =================== PREVIEW DE IMAGEN ===================
    const inputFoto = document.getElementById('input-foto');
    const btnAgregarImagen = document.getElementById('btn-agregar-imagen');
    const previewContainer = document.getElementById('preview-foto');
    const imgPreview = document.getElementById('img-preview');

    inputFoto.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imgPreview.src = e.target.result;
                previewContainer.style.display = 'block';
                btnAgregarImagen.classList.add('imagen-seleccionada');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // =================== ENVÍO FINAL DEL FORMULARIO (validación extra de respaldo) ===================
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validación final rápida (por si alguien manipuló el JS)
        const latitud = document.getElementById('latitud').value;
        const longitud = document.getElementById('longitud').value;
        if (!latitud || !longitud) {
            mostrarErrorTemporal('Debes marcar la ubicación en el mapa antes de finalizar.');
            return;
        }

        const formData = new FormData(form);

        fetch(window.routes.storeReporte, {
    method: 'POST',
    body: formData,
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')  // ← AQUÍ LA CLAVE
    }
})
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message || '¡Reporte creado con éxito!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let msg = 'Error al crear el reporte.\n';
            if (error.errors) {
                Object.values(error.errors).flat().forEach(m => msg += '• ' + m + '\n');
            } else if (error.message) {
                msg += error.message;
            }
            alert(msg);
        });
    });
});