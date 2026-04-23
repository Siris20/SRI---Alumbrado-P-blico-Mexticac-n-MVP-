<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProblema extends Model
{
    protected $table = 'tipos_problema';

    protected $fillable = [
        'nombre'
    ];

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'tipo_problema_id');
    }
}

