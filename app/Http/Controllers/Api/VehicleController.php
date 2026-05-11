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
            'pendingIssues',
            'reminders',
        ]);

        return new VehicleResource($vehicle);
    }

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
