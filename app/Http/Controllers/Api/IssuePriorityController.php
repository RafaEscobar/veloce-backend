<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IssuePriorityCollection;
use App\Models\IssuePriority;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de las prioridades de los problemas de los vehículos.
 *
 * Proporciona métodos para consultar los diferentes niveles de prioridad que se pueden asignar
 * a los problemas pendientes de los vehículos.
 *
 * @group Prioridades de problemas de vehículos
 */
class IssuePriorityController extends Controller
{
    /**
     * Listado de prioridades de problemas de vehículos.
     *
     * @authenticated
     * Recupera todos los registros de prioridades de problemas disponibles en el sistema
     * (por ejemplo: Baja, Media, Alta).
     *
     * @return IssuePriorityCollection Colección con las prioridades de problemas.
     */
    public function index(): IssuePriorityCollection
    {
        $priorities = IssuePriority::all();

        return new IssuePriorityCollection($priorities);
    }
}
