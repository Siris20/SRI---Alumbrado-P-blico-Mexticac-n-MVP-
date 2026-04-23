{{-- resources/views/reportes/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Reportes')

@push('styles')
    <link href="{{ asset('css/crear_reporte.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reportes.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="reportes-container">
    <!-- Botón flotante Nuevo Reporte -->
    <a href="#" id="btn-nuevo-reporte" class="btn-nuevo-reporte">
        Nuevo reporte <span class="plus">+</span>
    </a>

    @if(auth()->user()->email === 'admin@mexticacan.gob.mx')
    <div style="background:#d32f2f; color:white; padding:15px; border-radius:10px; margin-bottom:20px; text-align:center;">
        <strong>👨‍💼 Modo Administrador: Ves todos los reportes del sistema</strong>
    </div>
    @endif
    
    <div class="reportes-grid">
        <!-- Panel izquierdo: Pasos del reporte -->
        <div class="panel-nuevo">
            <div class="panel-title"><h2>SRI</h2></div>
            <div class="pasos" id="pasos-lateral">
                <div class="paso activo" data-paso="1">
                    <span class="numero">1</span>
                    <p>Descripción del problema</p>
                </div>
                <div class="paso" data-paso="2">
                    <span class="numero">2</span>
                    <p>Tipo de problema</p>
                </div>
                <div class="paso" data-paso="3">
                    <span class="numero">3</span>
                    <p>Evidencia Fotográfica</p>
                </div>
                <div class="paso" data-paso="4">
                    <span class="numero">4</span>
                    <p>Dirección (opcional)</p>
                </div>
                <div class="paso" data-paso="5">
                    <span class="numero">5</span>
                    <p>Ubicación en mapa</p>
                </div>
            </div>
        </div>

        <!-- Panel derecho: Lista o Formulario -->
        <div class="panel-reportes" id="panel-derecho">

            <!-- Lista de reportes (vista por defecto) -->
            <div id="lista-reportes" class="lista-reportes">
                @if($reportes->count() === 0)
                    <div style="text-align:center; padding:80px 20px; color:#999;">
                        @if($esAdmin ?? false)
                            <h3>No hay reportes en el sistema aún</h3>
                            <p>Cuando los ciudadanos reporten problemas, aparecerán aquí.</p>
                        @else
                            <h3>Aún no has creado ningún reporte</h3>
                            <p>Sé el primero en ayudar a mejorar Mexticacán.</p>
                            @if(($totalReportesSistema ?? 0) > 0)
                                <p style="margin-top:20px; color:#666;">
                                    Actualmente hay <strong>{{ $totalReportesSistema }}</strong> 
                                    reporte{{ $totalReportesSistema == 1 ? '' : 's' }} en el sistema.
                                </p>
                            @endif
                        @endif
                    </div>
                @else
                <!-- Filtros por estado (visibles para todos) -->
                <div style="margin: 30px 0; text-align: center;">
                    <strong>Filtrar por estado:</strong>
                    <button type="button" class="btn-filtro-estado" data-estado="todos" style="margin: 0 8px; padding: 8px 16px; border-radius: 20px; border: 1px solid #ccc; background: #f8f9fa; cursor: pointer;">
                        Todos
                    </button>
                    <button type="button" class="btn-filtro-estado" data-estado="1" style="margin: 0 8px; padding: 8px 16px; border-radius: 20px; border: 1px solid #ccc; background: #fff3cd; color: #856404; cursor: pointer;">
                        Iniciado
                    </button>
                    <button type="button" class="btn-filtro-estado" data-estado="2" style="margin: 0 8px; padding: 8px 16px; border-radius: 20px; border: 1px solid #ccc; background: #d1ecf1; color: #0c5460; cursor: pointer;">
                        En proceso
                    </button>
                    <button type="button" class="btn-filtro-estado" data-estado="3" style="margin: 0 8px; padding: 8px 16px; border-radius: 20px; border: 1px solid #ccc; background: #d4edda; color: #155724; cursor: pointer;">
                        Concluido
                    </button>
                </div>
                    @foreach($reportes as $reporte)
                    <div class="reporte-item" data-estado="{{ $reporte->estado_id }}">
                        <a href="{{ route('reportes.show', $reporte->id) }}" style="text-decoration:none; color:inherit; display:block;">
                            <div style="border:1px solid #ddd; border-radius:12px; padding:20px; margin-bottom:20px; background:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s;">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px;">
                                    <div style="flex:1;">
                                        <h4 style="margin:0 0 12px 0; color:#1976d2; font-size:1.3em;">
                                            {{ $reporte->tipoProblema->nombre }}
                                        </h4>

                                        <p style="margin:0 0 15px 0; color:#444; line-height:1.5;">
                                            <strong>Descripción:</strong> {{ $reporte->descripcion }}
                                        </p>

                                        @if($reporte->direccion)
                                            <p style="margin:8px 0; color:#555;">
                                                <strong>Dirección:</strong> {{ $reporte->direccion }}
                                            </p>
                                        @endif

                                        <p style="margin:8px 0;">
                                            <strong>Estado:</strong>
                                            <span style="
                                                padding:6px 14px; 
                                                border-radius:20px; 
                                                font-size:0.9em; 
                                                font-weight:bold;
                                                @if($reporte->estado_id == 1) background:#fff3cd; color:#856404;
                                                @elseif($reporte->estado_id == 2) background:#d1ecf1; color:#0c5460;
                                                @elseif($reporte->estado_id == 3) background:#d4edda; color:#155724;
                                                @endif
                                            ">
                                                @switch($reporte->estado_id)
                                                    @case(1) Iniciado @break
                                                    @case(2) En proceso @break
                                                    @case(3) Concluido @break
                                                    @default Desconocido
                                                @endswitch
                                            </span>
                                        </p>

                                        <small style="color:#888;">
                                            Reportado por <strong>{{ $reporte->usuario->name }}</strong> 
                                            el {{ $reporte->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>

                                    @if($reporte->imagen)
                                        <div>
                                            <img src="{{ Storage::url($reporte->imagen->ruta_imagen) }}" 
                                                alt="Evidencia del reporte" 
                                                style="width:220px; height:160px; object-fit:cover; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                    <div class="paginacion" style="margin-top: 40px; text-align: center;">
                        {{ $reportes->links('pagination::simple-tailwind') }}
                    </div>
                @endif
            </div>

            <!-- Formulario Nuevo Reporte (oculto al inicio) -->
            <div id="formulario-reporte" class="formulario-reporte" style="display:none;">
                <form id="form-nuevo-reporte">
                    <!-- Paso 1: Descripción -->
                    <div class="paso-form activo" data-paso="1">
                        <h3>Descripción del problema</h3>
                        <textarea name="descripcion" placeholder="Describa detalladamente el problema..." required class="input-descripcion" style="width:100%; height:150px; padding:12px; border-radius:8px; border:1px solid #ccc;"></textarea>

                        <div class="botones-form">
                            <button type="button" class="btn-continuar" data-siguiente="2">Continuar</button>
                        </div>
                    </div>

                    <!-- Paso 2: Tipo de problema -->
                    <div class="paso-form" data-paso="2">
                        <h3>Tipo de problema</h3>
                        <select name="tipo_problema_id" required class="input-select" style="width:100%; padding:12px; border-radius:8px;">
                            <option value="">Seleccione un tipo</option>
                            @foreach($tiposProblema as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>

                        <div class="botones-form">
                            <button type="button" class="btn-regresar" data-anterior="1">Regresar</button>
                            <button type="button" class="btn-continuar" data-siguiente="3">Continuar</button>
                        </div>
                    </div>

                    <!-- Paso 3: Evidencia Fotográfica -->
                    <div class="paso-form" data-paso="3">
                        <h3>Evidencia Fotográfica</h3>
                        <div class="evidencia-foto">
                            <p>Agregar imagen del problema</p>
                            
                            <label class="btn-agregar-imagen" id="btn-agregar-imagen">
                                <i class="fas fa-image"></i> <span>Seleccionar foto</span>
                                <input type="file" name="foto" accept="image/*" id="input-foto" style="display:none;" required>
                            </label>
                            
                            <div id="preview-foto" style="margin-top:30px; text-align:center; display:none;">
                                <img id="img-preview" alt="Preview" style="max-width:100%; max-height:400px; border-radius:10px;" />
                                <p style="margin-top:10px; color:#666; font-style:italic;">Imagen seleccionada</p>
                            </div>
                        </div>

                        <div class="botones-form">
                            <button type="button" class="btn-regresar" data-anterior="2">Regresar</button>
                            <button type="button" class="btn-continuar" data-siguiente="4">Continuar</button>
                        </div>
                    </div>

                    <!-- Paso 4: Dirección (opcional) -->
                    <div class="paso-form" data-paso="4">
                        <h3>Dirección del reporte (opcional)</h3>
                        <p style="color:#666; margin-bottom:20px;">Si conoces la dirección exacta, escríbela aquí. Ayuda a localizar mejor el problema.</p>
                        <input type="text" name="direccion" placeholder="Ej: Calle Hidalgo #123, Col. Centro, Mexticacán, Jalisco" 
                               style="width:100%; padding:14px; border-radius:8px; border:1px solid #ccc; font-size:1em;">

                        <div class="botones-form">
                            <button type="button" class="btn-regresar" data-anterior="3">Regresar</button>
                            <button type="button" class="btn-continuar" data-siguiente="5">Continuar al mapa</button>
                        </div>
                    </div>

                    <!-- Paso 5: Ubicación en mapa -->
                    <div class="paso-form" data-paso="5">
                        <h3>Ubicación en el mapa</h3>
                        <p style="text-align:center; color:#666; margin-bottom:20px;">Haz clic en el mapa para marcar la ubicación exacta del problema</p>
                        <div id="mapa" style="height:450px; border-radius:12px; margin:20px 0; box-shadow:0 4px 12px rgba(0,0,0,0.1);"></div>

                        <div class="botones-form">
                            <button type="button" class="btn-regresar" data-anterior="4">Regresar</button>
                            <button type="submit" class="btn-finalizar">Finalizar reporte</button>
                        </div>

                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Pasamos la ruta para el store -->
    <script>
        window.routes = {
            storeReporte: "{{ route('reportes.store') }}"
        };
    </script>
    
    <script src="{{ asset('js/crear_reporte.js') }}"></script>
    <script>
    document.querySelectorAll('.btn-filtro-estado').forEach(btn => {
        btn.addEventListener('click', function () {
            const estado = this.getAttribute('data-estado');
            
            // Resaltar botón activo
            document.querySelectorAll('.btn-filtro-estado').forEach(b => {
                b.style.fontWeight = 'normal';
                b.style.opacity = '0.7';
            });
            this.style.fontWeight = 'bold';
            this.style.opacity = '1';

            // Filtrar los reportes
            document.querySelectorAll('.reporte-item').forEach(item => {
                const estadoReporte = item.getAttribute('data-estado');
                
                if (estado === 'todos' || estado === estadoReporte) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush