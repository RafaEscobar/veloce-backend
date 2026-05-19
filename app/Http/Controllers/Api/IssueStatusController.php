<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\IssueStatusCollection;
use App\Models\IssueStatus;

/**
 * Controlador para la gestión de los estados de los problemas de los vehículos.
 *
 * Proporciona métodos para consultar los diferentes estados que pueden tener
 * los problemas pendientes de los vehículos.
 *
 * @group Estados de problemas de vehículos
 */
class IssueStatusController extends Controller
{
    /**
     * Listado de estados de problemas de vehículos.
     *
     * @authenticated
     * Recupera todos los registros de estados de problemas disponibles en el sistema
     * (por ejemplo: Pendiente, En proceso, Resuelto).
     *
     * @return IssueStatusCollection Colección con los estados de problemas.
     */
    public function index(): IssueStatusCollection
    {
        $issueStatuses = IssueStatus::all();

        return new IssueStatusCollection($issueStatuses);
    }
}
