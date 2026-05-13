<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reminder;

class ReminderPolicy
{
    /**
     * Determina si el usuario puede ver cualquier registro de recordatorios.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede ver un recordatorio específico.
     */
    public function view(User $user, Reminder $reminder): bool
    {
        return $user->id === $reminder->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede crear recordatorios.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede actualizar un recordatorio específico.
     */
    public function update(User $user, Reminder $reminder): bool
    {
        return $user->id === $reminder->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede eliminar un recordatorio específico.
     */
    public function delete(User $user, Reminder $reminder): bool
    {
        return $user->id === $reminder->vehicle->user_id;
    }
}
