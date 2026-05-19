<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Http\Resources\MaintenanceCollection;
use App\Http\Resources\MaintenanceResource;
use App\Models\Maintenance;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la gestión de mantenimientos de los vehículos de la API.
 *
 * Proporciona métodos para listar, crear, actualizar y eliminar registros de mantenimientos
 * asociados a los vehículos del usuario autenticado.
 *
 * @group Mantenimientos
 */
class MaintenanceController extends Controller
{
    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | authorizeResource()
        |--------------------------------------------------------------------------
        |
        | Vincula automáticamente los métodos del controlador con los de la Policy.
        |
        */
        $this->authorizeResource(Maintenance::class, 'maintenance');
    }

    /**
     * Listado de mantenimientos del usuario autenticado.
     *
     * @authenticated
     * Recupera un listado paginado de todos los mantenimientos registrados
     * para los vehículos del usuario autenticado, ordenados de forma descendente por fecha de creación.
     *
     * @param Request $request Objeto de petición actual.
     * @return MaintenanceCollection Colección paginada de mantenimientos.
     */
    public function index(Request $request): MaintenanceCollection
    {
        $maintenances = Maintenance::whereIn('vehicle_id', $request->user()->vehicles()->pluck('id'))
            ->latest()
            ->paginate();

        return new MaintenanceCollection($maintenances);
    }

    /**
     * Almacena un nuevo mantenimiento.
     *
     * @authenticated
     * Crea un nuevo registro de mantenimiento asociado a un vehículo específico del usuario.
     * Verifica que el vehículo pertenezca al usuario antes de proceder a la creación.
     *
     * @bodyParam vehicle_id int required ID del vehículo al que pertenece el mantenimiento. Example: 1
     * @bodyParam name string required Nombre o tipo de mantenimiento. Example: Cambio de aceite y filtro
     * @bodyParam date string required Fecha del mantenimiento en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam cost float Monto total del costo del mantenimiento. Example: 120.50
     * @bodyParam is_reminder_enabled boolean Indica si se debe habilitar un recordatorio para este mantenimiento. Example: true
     * @bodyParam notes string Notas adicionales sobre el mantenimiento realizado. Example: Se usó aceite sintético 5W-30.
     *
     * @param StoreMaintenanceRequest $request Objeto de petición con los datos de mantenimiento validados.
     * @return MaintenanceResource Recurso del mantenimiento creado.
     */
    public function store(StoreMaintenanceRequest $request): MaintenanceResource
    {
        $validated = $request->validated();
        /*
        | SEGURIDAD: Verificamos que el vehículo pertenezca al usuario autenticado.
        */
        $request->user()->vehicles()->findOrFail($validated['vehicle_id']);

        /*
        | Creamos el registro y lo recargamos para obtener los datos completos
        | incluyendo los campos autogenerados como el ID.
        */
        $maintenance = Maintenance::create($validated)->refresh();

        return new MaintenanceResource($maintenance);
    }

    /**
     * Actualiza un mantenimiento existente.
     *
     * @authenticated
     * Modifica los datos de un mantenimiento previamente registrado.
     * Si se intenta cambiar el vehículo, verifica que el nuevo vehículo pertenezca al usuario autenticado.
     *
     * @urlParam id integer required ID del registro de mantenimiento. Example: 1
     *
     * @bodyParam vehicle_id int ID del vehículo al que pertenece el mantenimiento. Example: 1
     * @bodyParam name string Nombre o tipo de mantenimiento. Example: Cambio de aceite y filtro
     * @bodyParam date string Fecha del mantenimiento en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam cost float Monto total del costo del mantenimiento. Example: 130.00
     * @bodyParam is_reminder_enabled boolean Indica si se debe habilitar un recordatorio para este mantenimiento. Example: false
     * @bodyParam notes string Notas adicionales sobre el mantenimiento realizado. Example: Se usó aceite sintético 5W-30.
     *
     * @param UpdateMaintenanceRequest $request Objeto de petición con los datos a actualizar.
     * @param Maintenance $maintenance Modelo del mantenimiento a actualizar.
     * @return MaintenanceResource Recurso del mantenimiento modificado.
     */
    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance): MaintenanceResource
    {
        $validated = $request->validated();

        /*
        | Si se intenta cambiar el vehículo, verificamos que el nuevo vehículo
        | también pertenezca al usuario.
        */
        if (isset($validated['vehicle_id'])) {
            $request->user()->vehicles()->findOrFail($validated['vehicle_id']);
        }

        $maintenance->update($validated);

        return new MaintenanceResource($maintenance);
    }

    /**
     * Elimina un mantenimiento.
     *
     * @authenticated
     * Elimina de la base de datos el registro de mantenimiento especificado.
     * La autorización del recurso se realiza automáticamente a través de la Policy vinculada.
     *
     * @urlParam id integer required ID del registro de mantenimiento. Example: 1
     *
     * @param Maintenance $maintenance Modelo del mantenimiento a eliminar.
     * @return JsonResponse Respuesta JSON que confirma la eliminación correcta del mantenimiento.
     */
    public function destroy(Maintenance $maintenance): JsonResponse
    {
        $maintenance->delete();

        return response()->json([
            'message' => 'Mantenimiento eliminado correctamente.',
        ]);
    }
}



