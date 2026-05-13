<?php

namespace App\Policies;

use App\Models\Maintenance;
use App\Models\User;

class MaintenancePolicy
{
    /**
     * Determina si el usuario puede ver cualquier registro de mantenimiento.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede ver un mantenimiento específico.
     */
    public function view(User $user, Maintenance $maintenance): bool
    {
        return $user->id === $maintenance->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede crear mantenimientos.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede actualizar un mantenimiento específico.
     */
    public function update(User $user, Maintenance $maintenance): bool
    {
        return $user->id === $maintenance->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede eliminar un mantenimiento específico.
     */
    public function delete(User $user, Maintenance $maintenance): bool
    {
        return $user->id === $maintenance->vehicle->user_id;
    }
}
