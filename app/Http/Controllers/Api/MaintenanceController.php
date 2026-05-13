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
     * Display a listing of the resource.
     */
    public function index(Request $request): MaintenanceCollection
    {
        $maintenances = Maintenance::whereIn('vehicle_id', $request->user()->vehicles()->pluck('id'))
            ->latest()
            ->paginate();

        return new MaintenanceCollection($maintenances);
    }

    /**
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance): JsonResponse
    {
        $maintenance->delete();

        return response()->json([
            'message' => 'Mantenimiento eliminado correctamente.',
        ]);
    }
}


