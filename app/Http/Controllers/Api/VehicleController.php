<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleCollection;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function index(): VehicleCollection
    {
        $vehicles = Vehicle::query()
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

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create($validated);

        return new VehicleResource($vehicle);
    }

    public function show(Vehicle $vehicle): VehicleResource
    {
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
        $vehicle->update($request->validated());
        return new VehicleResource($vehicle);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();
        return response()->json([
            'message' => 'Vehículo eliminado correctamente.',
        ]);
    }

}
