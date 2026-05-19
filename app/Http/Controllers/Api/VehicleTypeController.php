<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\VehicleTypeCollection;
use App\Models\VehicleType;

/**
 * Controlador para la gestión de los tipos de vehículos.
 *
 * Proporciona métodos para consultar los diferentes tipos de vehículos que pueden
 * registrarse en el sistema.
 *
 * @group Tipos de vehículos
 */
class VehicleTypeController extends Controller
{
    /**
     * Listado de tipos de vehículos.
     *
     * @authenticated
     * Recupera un listado paginado de todos los tipos de vehículos disponibles en el sistema
     * (por ejemplo: Automóvil, Motocicleta, Camioneta).
     *
     * @return VehicleTypeCollection Colección paginada con los tipos de vehículos.
     */
    public function index(): VehicleTypeCollection
    {
        $vehicleTypes = VehicleType::paginate();

        return new VehicleTypeCollection($vehicleTypes);
    }
}
