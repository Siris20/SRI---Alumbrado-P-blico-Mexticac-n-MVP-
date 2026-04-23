<?php

namespace App\Policies;

use App\Models\Reporte;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportePolicy
{
    use HandlesAuthorization;

    // El admin puede hacer todo
    public function before(User $user, $ability)
    {
        if ($user->email === 'admin@mexticacan.gob.mx') {
            return true;
        }
    }

    // Ciudadanos pueden ver solo sus propios reportes
    public function view(User $user, Reporte $reporte)
    {
        return $user->id === $reporte->usuario_id;
    }

    // Solo admin puede actualizar y eliminar
    public function update(User $user, Reporte $reporte)
    {
        return false; // o true si quieres que ciudadanos editen (no recomendado)
    }

    public function delete(User $user, Reporte $reporte)
    {
        return false;
    }
}