<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Http\Resources\ReminderCollection;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\JsonResponse;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Reminder::class, 'reminder');
    }

    /**
     * Listado de recordatorios del usuario autenticado.
     */
    public function index(Request $request): ReminderCollection
    {
        $reminders = Reminder::whereIn('vehicle_id', $request->user()->vehicles()->pluck('id'))
            ->latest()
            ->paginate();

        return new ReminderCollection($reminders);
    }

    /**
     * Almacena un nuevo recordatorio.
     */
    public function store(StoreReminderRequest $request): ReminderResource
    {
        $validated = $request->validated();

        // Verificar que el vehículo pertenezca al usuario
        $request->user()->vehicles()->findOrFail($validated['vehicle_id']);

        $reminder = Reminder::create($validated);

        return new ReminderResource($reminder);
    }

    /**
     * Actualiza un recordatorio existente.
     */
    public function update(UpdateReminderRequest $request, Reminder $reminder): ReminderResource
    {
        $validated = $request->validated();

        if (isset($validated['vehicle_id'])) {
            $request->user()->vehicles()->findOrFail($validated['vehicle_id']);
        }

        $reminder->update($validated);

        return new ReminderResource($reminder);
    }

    /**
     * Elimina un recordatorio.
     */
    public function destroy(Reminder $reminder): JsonResponse
    {
        $reminder->delete();

        return response()->json([
            'message' => 'Recordatorio eliminado correctamente.',
        ]);
    }
}
