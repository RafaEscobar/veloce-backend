<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGasolineRefillRequest;
use App\Http\Requests\UpdateGasolineRefillRequest;
use App\Http\Resources\GasolineRefillCollection;
use App\Http\Resources\GasolineRefillResource;
use App\Models\GasolineRefill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de recargas de gasolina de los vehículos de la API.
 *
 * Proporciona métodos para listar, crear, actualizar y eliminar registros de recargas
 * de gasolina asociados a los vehículos del usuario autenticado.
 * @group Cargas de gasolina
 */
class GasolineRefillController extends Controller
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
        $this->authorizeResource(GasolineRefill::class, 'gasoline_refill');
    }

    /**
     * Listado de recargas de gasolina del usuario autenticado.
     *
     * @authenticated
     * Recupera un listado paginado de todas las recargas de gasolina registradas
     * para los vehículos del usuario autenticado, ordenadas de forma descendente por fecha de creación.
     *
     * @param Request $request Objeto de petición actual.
     * @return GasolineRefillCollection Colección paginada de recargas de gasolina.
     */
    public function index(Request $request): GasolineRefillCollection
    {
        /*
        | Filtramos las recargas a través de los vehículos que pertenecen al usuario.
        */
        $refills = GasolineRefill::whereIn('vehicle_id', $request->user()->vehicles()->pluck('id'))
            ->latest()
            ->paginate();

        return new GasolineRefillCollection($refills);
    }

    /**
     * Almacena una nueva recarga de gasolina.
     *
     * @authenticated
     * Crea un nuevo registro de recarga de gasolina asociado a un vehículo específico del usuario.
     * Verifica que el vehículo pertenezca al usuario antes de proceder a la creación.
     *
     * @bodyParam vehicle_id int required ID del vehículo al que pertenece la recarga. Example: 1
     * @bodyParam amount float required Monto total gastado en la recarga. Example: 45.50
     * @bodyParam liters float required Cantidad de litros de gasolina recargados. Example: 25.4
     * @bodyParam date string Fecha de la recarga en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam gas_station string Nombre o ubicación de la gasolinera. Example: Repsol Centro
     *
     * @param StoreGasolineRefillRequest $request Objeto de petición con los datos de recarga validados.
     * @return GasolineRefillResource Recurso de la recarga de gasolina creada.
     */
    public function store(StoreGasolineRefillRequest $request): GasolineRefillResource
    {
        $validated = $request->validated();

        /*
        | SEGURIDAD: Verificamos que el vehículo pertenezca al usuario autenticado.
        | findOrFail lanzará una excepción 404 si el vehículo no existe o no es del usuario.
        */
        $request->user()->vehicles()->findOrFail($validated['vehicle_id']);

        $refill = GasolineRefill::create($validated);

        return new GasolineRefillResource($refill);
    }

    /**
     * Actualiza una recarga existente.
     *
     * @authenticated
     * Modifica los datos de una recarga de gasolina previamente registrada.
     * Si se intenta cambiar el vehículo, verifica que el nuevo vehículo pertenezca al usuario autenticado.
     *
     * @urlParam id integer required ID del registro de gasolina. Example: 1
     *
     * @bodyParam amount float Monto total gastado en la recarga. Example: 50.00
     * @bodyParam liters float Cantidad de litros de gasolina recargados. Example: 28.0
     * @bodyParam date string Fecha de la recarga en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam gas_station string Nombre o ubicación de la gasolinera. Example: Repsol Centro
     *
     * @param UpdateGasolineRefillRequest $request Objeto de petición con los datos a actualizar.
     * @param GasolineRefill $gasolineRefill Modelo de la recarga a actualizar.
     * @return GasolineRefillResource Recurso de la recarga de gasolina modificada.
     */
    public function update(UpdateGasolineRefillRequest $request, GasolineRefill $gasolineRefill): GasolineRefillResource
    {
        $validated = $request->validated();

        /*
        | Si se intenta cambiar el vehículo, verificamos que el nuevo vehículo
        | también pertenezca al usuario.
        */
        if (isset($validated['vehicle_id'])) {
            $request->user()->vehicles()->findOrFail($validated['vehicle_id']);
        }

        $gasolineRefill->update($validated);

        return new GasolineRefillResource($gasolineRefill);
    }

    /**
     * Elimina una recarga.
     *
     * @authenticated
     * Elimina de la base de datos el registro de recarga de gasolina especificado.
     * La autorización del recurso se realiza automáticamente a través de la Policy vinculada.
     *
     * @urlParam id integer required ID del registro de gasolina. Example: 1
     *
     * @param GasolineRefill $gasolineRefill Modelo de la recarga de gasolina a eliminar.
     * @return JsonResponse Respuesta JSON que confirma la eliminación correcta de la recarga.
     */
    public function destroy(GasolineRefill $gasolineRefill): JsonResponse
    {
        // La autorización ocurre automáticamente por authorizeResource
        $gasolineRefill->delete();

        return response()->json([
            'message' => 'Registro de gasolina eliminado correctamente.',
        ]);
    }
}
