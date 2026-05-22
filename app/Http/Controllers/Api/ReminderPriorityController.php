<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReminderPriorityCollection;
use App\Models\ReminderPriority;

/**
 * Controlador para la gestión de las prioridades de recordatorios.
 *
 * @group Prioridades de recordatorios
 */
class ReminderPriorityController extends Controller
{
    /**
     * Listado de prioridades de recordatorios.
     *
     * @authenticated
     *
     * @return ReminderPriorityCollection Colección con las prioridades de recordatorios.
     */
    public function index(): ReminderPriorityCollection
    {
        $priorities = ReminderPriority::all();

        return new ReminderPriorityCollection($priorities);
    }
}
