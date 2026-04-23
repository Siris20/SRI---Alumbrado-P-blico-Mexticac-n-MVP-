<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoReporte extends Model
{
    protected $table = 'estados_reporte';

    protected $fillable = [
        'nombre',
        'color'
    ];

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'estado_id');
    }
}

