<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenReporte extends Model
{
    protected $table = 'imagen_reporte';

    protected $fillable = [
        'reporte_id',
        'ruta_imagen'
    ];

    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }
}

