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
