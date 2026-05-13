<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PendingIssue;

class PendingIssuePolicy
{
    /**
     * Determina si el usuario puede ver cualquier registro de problemas pendientes.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede ver un problema pendiente específico.
     */
    public function view(User $user, PendingIssue $pendingIssue): bool
    {
        return $user->id === $pendingIssue->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede crear problemas pendientes.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede actualizar un problema pendiente específico.
     */
    public function update(User $user, PendingIssue $pendingIssue): bool
    {
        return $user->id === $pendingIssue->vehicle->user_id;
    }

    /**
     * Determina si el usuario puede eliminar un problema pendiente específico.
     */
    public function delete(User $user, PendingIssue $pendingIssue): bool
    {
        return $user->id === $pendingIssue->vehicle->user_id;
    }
}
