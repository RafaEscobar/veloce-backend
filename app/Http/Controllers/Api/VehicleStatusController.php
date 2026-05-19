<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\VehicleStatusCollection;
use App\Models\VehicleStatus;

/**
 * Controlador para la gestión de los estados de los vehículos.
 *
 * Proporciona métodos para consultar los diferentes estados en los que puede
 * encontrarse un vehículo registrado en el sistema.
 *
 * @group Estados de vehículos
 */
class VehicleStatusController extends Controller
{
    /**
     * Listado de estados de vehículos.
     *
     * @authenticated
     * Recupera todos los registros de estados de vehículos disponibles en el sistema
     * (por ejemplo: Activo, En taller, Inactivo).
     *
     * @return VehicleStatusCollection Colección con los estados de vehículos.
     */
    public function index(): VehicleStatusCollection
    {
        $vehicleStatuses = VehicleStatus::all();

        return new VehicleStatusCollection($vehicleStatuses);
    }
}
