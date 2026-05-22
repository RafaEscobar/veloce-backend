<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleCollection;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador para la gestión de vehículos.
 *
 * Proporciona métodos para listar, crear, mostrar, actualizar y eliminar vehículos
 * pertenecientes al usuario autenticado.
 *
 * @group Vehículos
 */
class VehicleController extends Controller
{

    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | authorizeResource()
        |--------------------------------------------------------------------------
        |
        | Vincula automáticamente los métodos del controlador con los de la Policy.
        | - index()   -> viewAny()
        | - store()   -> create()
        | - show()    -> view()
        | - update()  -> update()
        | - destroy() -> delete()
        |
        | Esto evita tener que llamar a $this->authorize() manualmente en cada método.
        */
        $this->authorizeResource(Vehicle::class, 'vehicle');
    }

    /**
     * Lista los vehículos del usuario autenticado.
     *
     * Devuelve una colección paginada de todos los vehículos que pertenecen
     * al usuario que realiza la petición, incluyendo sus relaciones (tipo y estado).
     *
     * @param Request $request La petición HTTP actual.
     * @return VehicleCollection Colección paginada de recursos de vehículos.
     */
    public function index(Request $request): VehicleCollection
    {
        /*
        | Ya filtrado por usuario autenticado mediante la relación
        */
        $vehicles = $request->user()
            ->vehicles()
            ->with([
                'vehicleType',
                'vehicleStatus',
            ])
            ->latest()
            ->paginate();

        return new VehicleCollection($vehicles);
    }

    /**
     * Crea un nuevo vehículo.
     *
     * Registra un nuevo vehículo en el sistema asociándolo automáticamente al usuario autenticado.
     * Si se proporciona una foto, la almacena en el disco público.
     *
     * @bodyParam vehicle_type_id integer required ID del tipo de vehículo. Example: 1
     * @bodyParam vehicle_status_id integer required ID del estado del vehículo. Example: 1
     * @bodyParam name string required Nombre o alias del vehículo. Example: Mi Coche
     * @bodyParam plates string Placas del vehículo. Example: ABC-1234
     * @bodyParam serial_number string Número de serie (VIN). Example: 1HGCM82633A000000
     * @bodyParam gasoline_type string Tipo de gasolina que utiliza. Example: Magna
     * @bodyParam oil_type string Tipo de aceite que utiliza. Example: Sintético 5W-30
     * @bodyParam model_name string Nombre del modelo del vehículo. Example: Civic
     * @bodyParam photo file Foto del vehículo (imagen, máx. 2MB).
     * @bodyParam model_year integer Año del modelo del vehículo. Example: 2020
     *
     * @param StoreVehicleRequest $request Objeto de petición que contiene los datos validados del vehículo.
     * @return VehicleResource Recurso JSON del vehículo creado.
     */
    public function store(StoreVehicleRequest $request): VehicleResource
    {
        $validated = $request->validated();

        /*
        | ASIGNACIÓN SEGURA DE PROPIETARIO
        |
        | Ignoramos cualquier user_id enviado por el cliente y forzamos
        | el ID del usuario autenticado.
        */
        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create($validated);

        return new VehicleResource($vehicle);
    }

    /**
     * Muestra los detalles de un vehículo específico.
     *
     * Devuelve la información de un vehículo junto con todas sus relaciones cargadas
     * (tipo, estado, cargas de gasolina, mantenimientos y recordatorios).
     *
     * @urlParam id integer required ID del vehículo. Example: 1
     *
     * @param Vehicle $vehicle La instancia del vehículo a mostrar.
     * @return VehicleResource Recurso JSON con los detalles del vehículo.
     */
    public function show(Vehicle $vehicle): VehicleResource
    {
        /*
        | La autorización (IDOR check) ocurre automáticamente gracias a authorizeResource
        */
        $vehicle->load([
            'vehicleType',
            'vehicleStatus',
            'gasolineRefills',
            'maintenances',
            'reminders',
        ]);

        return new VehicleResource($vehicle);
    }

    /**
     * Actualiza un vehículo existente.
     *
     * Modifica los datos de un vehículo perteneciente al usuario autenticado.
     * Si se sube una nueva foto, reemplaza la anterior eliminándola del almacenamiento.
     *
     * @urlParam id integer required ID del vehículo. Example: 1
     *
     * @bodyParam vehicle_type_id integer ID del tipo de vehículo. Example: 1
     * @bodyParam vehicle_status_id integer ID del estado del vehículo. Example: 1
     * @bodyParam name string Nombre o alias del vehículo. Example: Mi Coche Editado
     * @bodyParam plates string Placas del vehículo. Example: XYZ-9876
     * @bodyParam serial_number string Número de serie (VIN). Example: 1HGCM82633A000000
     * @bodyParam gasoline_type string Tipo de gasolina que utiliza. Example: Premium
     * @bodyParam oil_type string Tipo de aceite que utiliza. Example: Sintético 10W-40
     * @bodyParam model_name string Nombre del modelo del vehículo. Example: Accord
     * @bodyParam photo file Nueva foto del vehículo (imagen, máx. 2MB).
     * @bodyParam model_year integer Año del modelo del vehículo. Example: 2022
     *
     * @param UpdateVehicleRequest $request Objeto de petición que contiene los datos validados a actualizar.
     * @param Vehicle $vehicle La instancia del vehículo a actualizar.
     * @return VehicleResource Recurso JSON del vehículo actualizado.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): VehicleResource
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            /*
            | Si se sube una nueva foto, eliminamos la anterior para no dejar
            | archivos huérfanos en el almacenamiento.
            */
            if ($vehicle->photo) {
                Storage::disk('public')->delete($vehicle->photo);
            }

            $validated['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        $vehicle->update($validated);

        return new VehicleResource($vehicle);
    }

    /**
     * Elimina un vehículo.
     *
     * Borra el registro del vehículo especificado. También elimina la foto
     * asociada del almacenamiento si existe.
     *
     * @urlParam id integer required ID del vehículo. Example: 1
     *
     * @param Vehicle $vehicle La instancia del vehículo a eliminar.
     * @return JsonResponse Respuesta JSON con mensaje de éxito.
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        /*
        | Eliminamos la foto asociada antes de borrar el registro
        */
        if ($vehicle->photo) {
            Storage::disk('public')->delete($vehicle->photo);
        }

        $vehicle->delete();

        return response()->json([
            'message' => 'Vehículo eliminado correctamente.',
        ]);
    }
}
