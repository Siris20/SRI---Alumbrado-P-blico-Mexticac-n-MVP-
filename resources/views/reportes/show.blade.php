{{-- resources/views/reportes/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detalle del Reporte #' . $reporte->id)

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
    <h1 style="text-align:center; color:#1976d2; margin-bottom:30px;">
        Detalle del Reporte #{{ $reporte->id }}
    </h1>

    <div style="background:#fff; border-radius:12px; padding:30px; box-shadow:0 8px 20px rgba(0,0,0,0.1);">
        <div style="display:flex; flex-wrap:wrap; gap:30px;">
            <!-- Información textual -->
            <div style="flex:1; min-width:300px;">
                <p><strong>Tipo de problema:</strong> {{ $reporte->tipoProblema->nombre }}</p>
                <p><strong>Descripción:</strong><br> {{ $reporte->descripcion }}</p>

                @if($reporte->direccion)
                    <p><strong>Dirección:</strong> {{ $reporte->direccion }}</p>
                @endif

                <p><strong>Ubicación:</strong> Lat: {{ $reporte->latitud }}, Lng: {{ $reporte->longitud }}</p>

                <p><strong>Estado actual:</strong>
                    <span style="padding:6px 14px; border-radius:20px; font-weight:bold;
                        @if($reporte->estado_id == 1) background:#fff3cd; color:#856404;
                        @elseif($reporte->estado_id == 2) background:#d1ecf1; color:#0c5460;
                        @elseif($reporte->estado_id == 3) background:#d4edda; color:#155724;
                        @endif">
                        @switch($reporte->estado_id)
                            @case(1) Iniciado @break
                            @case(2) En proceso @break
                            @case(3) Concluido @break
                        @endswitch
                    </span>
                </p>

                <p><strong>Reportado por:</strong> {{ $reporte->usuario->name }}</p>
                <p><strong>Fecha:</strong> {{ $reporte->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Imagen -->
            @if($reporte->imagen)
                <div style="flex:1; min-width:300px; text-align:center;">
                    <img src="{{ Storage::url($reporte->imagen->ruta_imagen) }}" 
                         alt="Evidencia" 
                         style="max-width:100%; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                </div>
            @endif
        </div>

        <!-- Mapa estático con la ubicación -->
        <div style="margin-top:30px;">
            <h3>Ubicación en el mapa</h3>
            <div id="mapa-detalle" style="height:400px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);"></div>
        </div>

        <!-- Opciones solo para admin -->
        @if(auth()->user()->email === 'admin@mexticacan.gob.mx')
            <hr style="margin:40px 0;">
            <h3 style="color:#1976d2;">Acciones de administrador</h3>

            <!-- Cambiar estado -->
            <div style="margin-bottom:20px;">
                <label><strong>Cambiar estado:</strong></label>
                <select id="select-estado" style="padding:10px; border-radius:6px; margin-left:10px;">
                    <option value="1" {{ $reporte->estado_id == 1 ? 'selected' : '' }}>Iniciado</option>
                    <option value="2" {{ $reporte->estado_id == 2 ? 'selected' : '' }}>En proceso</option>
                    <option value="3" {{ $reporte->estado_id == 3 ? 'selected' : '' }}>Concluido</option>
                </select>
                <button id="btn-actualizar-estado" style="padding:10px 20px; background:#007bff; color:white; border:none; border-radius:6px; margin-left:10px; cursor:pointer;">
                    Actualizar estado
                </button>
            </div>

            <!-- Eliminar -->
            <button id="btn-eliminar-reporte" style="padding:10px 20px; background:#dc3545; color:white; border:none; border-radius:6px; cursor:pointer;">
                <i class="fas fa-trash-alt"></i> Eliminar reporte
            </button>
        @endif

        <div style="margin-top:30px; text-align:center;">
            <a href="{{ route('reportes.index') }}" style="color:#666; text-decoration:underline;">← Volver a la lista de reportes</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Rutas
            window.routes = {
                index: "{{ route('reportes.index') }}",
                updateEstado: "{{ route('reportes.updateEstado', $reporte->id) }}",
                destroy: "/reportes/{{ $reporte->id }}"
            };

            // Coordenadas con parseFloat
            const lat = parseFloat("{{ $reporte->latitud }}");
            const lng = parseFloat("{{ $reporte->longitud }}");

            if (isNaN(lat) || isNaN(lng)) {
                console.error('Coordenadas inválidas:', lat, lng);
                return;
            }

            // Inicializamos el mapa
            const mapa = L.map('mapa-detalle').setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(mapa);

            L.marker([lat, lng])
                .addTo(mapa)
                .bindPopup('<strong>Ubicación del reporte</strong>')
                .openPopup();

            // IMPORTANTE: Invalidamos el tamaño después de un pequeño delay
            // Esto soluciona el problema de mapa gris/roto cuando el div estaba oculto o no renderizado
            setTimeout(function () {
                mapa.invalidateSize();
            }, 100);

            @if(auth()->user()->email === 'admin@mexticacan.gob.mx')
                // Actualizar estado
                const btnActualizar = document.getElementById('btn-actualizar-estado');
                if (btnActualizar) {
                    btnActualizar.addEventListener('click', function () {
                        const nuevoEstado = document.getElementById('select-estado').value;

                        fetch(window.routes.updateEstado, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ estado_id: nuevoEstado })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Estado actualizado correctamente');
                                location.reload();
                            } else {
                                alert('Error al actualizar');
                            }
                        })
                        .catch(() => alert('Error de conexión'));
                    });
                }

                // Eliminar reporte
                const btnEliminar = document.getElementById('btn-eliminar-reporte');
                if (btnEliminar) {
                    btnEliminar.addEventListener('click', function () {
                        if (!confirm('¿Seguro que quieres eliminar este reporte?')) return;

                        fetch(window.routes.destroy, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Reporte eliminado');
                                window.location.href = window.routes.index;
                            }
                        })
                        .catch(() => alert('Error al eliminar'));
                    });
                }
            @endif
        });
    </script>
@endpush