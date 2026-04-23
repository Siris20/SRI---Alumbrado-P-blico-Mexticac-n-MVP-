<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\ImagenReporte;
use App\Models\TipoProblema;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    use AuthorizesRequests;

    /**
     * Muestra la vista principal con formulario y lista
     */
   public function index()
{
    $tiposProblema = TipoProblema::all();

    $esAdmin = Auth::user()->email === 'admin@mexticacan.gob.mx';

    if ($esAdmin) {
        // Admin ve todos los reportes con paginación
        $reportes = Reporte::with(['tipoProblema', 'estado', 'imagen', 'usuario'])
                          ->latest()
                          ->paginate(5); // ← Aquí la paginación
    } else {
        // Ciudadano ve solo los suyos con paginación
        $reportes = Auth::user()->reportes()
                          ->with(['tipoProblema', 'estado', 'imagen'])
                          ->latest()
                          ->paginate(5);
    }

    $totalReportesSistema = Reporte::count();

    return view('reportes.index', compact(
        'reportes',
        'tiposProblema',
        'esAdmin',
        'totalReportesSistema'
    ));
}

    /**
     * Guarda un nuevo reporte (AJAX)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_problema_id' => 'required|exists:tipos_problema,id',
            'descripcion'      => 'required|string|max:1000',
            'latitud'          => 'required|numeric|between:-90,90',
            'longitud'         => 'required|numeric|between:-180,180',
            'direccion'        => 'nullable|string|max:255',
            'foto'             => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Crear reporte
        $reporte = Reporte::create([
            'usuario_id'       => Auth::id(),
            'tipo_problema_id' => $validated['tipo_problema_id'],
            'descripcion'      => $validated['descripcion'],
            'estado_id'        => 1, // Ajusta según tu tabla de estados (1 = Pendiente/Iniciado)
            'latitud'          => $validated['latitud'],
            'longitud'         => $validated['longitud'],
            'direccion'        => $validated['direccion'] ?? null,
        ]);

        // Guardar imagen
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('reportes', 'public');

            ImagenReporte::create([
                'reporte_id'   => $reporte->id,
                'ruta_imagen' => $path,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => '¡Reporte creado exitosamente!',
            'reporte' => $reporte->load(['tipoProblema', 'imagen'])
        ]);
    }

    /**
     * Mostrar un reporte individual (opcional)
     */
    public function show(Reporte $reporte)
    {
        $this->authorize('view', $reporte);
        return view('reportes.show', compact('reporte'));
    }

    /**
     * Eliminar reporte (AJAX)
     */
    public function destroy(Reporte $reporte)
    {
        $this->authorize('delete', $reporte);

        if ($reporte->imagen) {
            Storage::disk('public')->delete($reporte->imagen->ruta_imagen);
            $reporte->imagen->delete();
        }

        $reporte->delete();

        return response()->json(['success' => true, 'message' => 'Reporte eliminado']);
    }

    public function updateEstado(Request $request, Reporte $reporte)
{
    $this->authorize('update', $reporte); // opcional, si tienes policy

    $request->validate([
        'estado_id' => 'required|in:1,2,3'
    ]);

    $reporte->update(['estado_id' => $request->estado_id]);

    return response()->json(['success' => true]);
}
}