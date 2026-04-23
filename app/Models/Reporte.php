<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reportes';

    protected $fillable = [
        'usuario_id',
        'tipo_problema_id',
        'estado_id',
        'descripcion',     // ← ¡Agrégalo aquí!
        'latitud',
        'longitud',
        'direccion'
    ];

    // Relaciones (están bien)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tipoProblema()
    {
        return $this->belongsTo(TipoProblema::class, 'tipo_problema_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoReporte::class, 'estado_id');
    }

    public function imagen()
    {
        return $this->hasOne(ImagenReporte::class, 'reporte_id');
    }
}