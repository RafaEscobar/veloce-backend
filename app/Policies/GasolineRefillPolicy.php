<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GasolineRefill;

class GasolineRefillPolicy
{
    /**
     * Determina si el usuario puede ver cualquier registro de recarga.
     */
    public function viewAny(User $user): bool
    {
        // El filtrado por usuario se realiza en el controlador
        return true;
    }

    /**
     * Determina si el usuario puede ver una recarga específica.
     */
    public function view(User $user, GasolineRefill $gasolineRefill): bool
    {
        return $user->id === $gasolineRefill->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede crear recargas.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede actualizar una recarga específica.
     */
    public function update(User $user, GasolineRefill $gasolineRefill): bool
    {
        return $user->id === $gasolineRefill->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede eliminar una recarga específica.
     */
    public function delete(User $user, GasolineRefill $gasolineRefill): bool
    {
        return $user->id === $gasolineRefill->vehicle->user_id;
    }
}
