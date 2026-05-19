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

/**
 * Controlador para la gestión de recordatorios de los vehículos de la API.
 *
 * Proporciona métodos para listar, crear, actualizar y eliminar registros de recordatorios
 * asociados a los vehículos del usuario autenticado.
 *
 * @group Recordatorios
 */
class ReminderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Reminder::class, 'reminder');
    }

    /**
     * Listado de recordatorios del usuario autenticado.
     *
     * @authenticated
     * Recupera un listado paginado de todos los recordatorios registrados
     * para los vehículos del usuario autenticado, ordenados de forma descendente por fecha de creación.
     *
     * @param Request $request Objeto de petición actual.
     * @return ReminderCollection Colección paginada de recordatorios.
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
     *
     * @authenticated
     * Crea un nuevo registro de recordatorio asociado a un vehículo específico del usuario.
     * Verifica que el vehículo pertenezca al usuario antes de proceder a la creación.
     *
     * @bodyParam vehicle_id int required ID del vehículo al que pertenece el recordatorio. Example: 1
     * @bodyParam name string required Título o nombre corto del recordatorio. Example: Renovación de seguro
     * @bodyParam date string required Fecha programada para el recordatorio en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam description string Descripción detallada u observaciones del recordatorio. Example: Renovar póliza anual con cobertura amplia.
     *
     * @param StoreReminderRequest $request Objeto de petición con los datos de recordatorio validados.
     * @return ReminderResource Recurso del recordatorio creado.
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
     *
     * @authenticated
     * Modifica los datos de un recordatorio previamente registrado.
     * Si se intenta cambiar el vehículo, verifica que el nuevo vehículo pertenezca al usuario autenticado.
     *
     * @urlParam id integer required ID del registro de recordatorio. Example: 1
     *
     * @bodyParam vehicle_id int ID del vehículo al que pertenece el recordatorio. Example: 1
     * @bodyParam name string Título o nombre corto del recordatorio. Example: Renovación de seguro
     * @bodyParam date string Fecha programada para el recordatorio en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam description string Descripción detallada u observaciones del recordatorio. Example: Renovar póliza anual con cobertura amplia.
     *
     * @param UpdateReminderRequest $request Objeto de petición con los datos a actualizar.
     * @param Reminder $reminder Modelo del recordatorio a actualizar.
     * @return ReminderResource Recurso del recordatorio modificado.
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
     *
     * @authenticated
     * Elimina de la base de datos el registro de recordatorio especificado.
     * La autorización del recurso se realiza automáticamente a través de la Policy vinculada.
     *
     * @urlParam id integer required ID del registro de recordatorio. Example: 1
     *
     * @param Reminder $reminder Modelo del recordatorio a eliminar.
     * @return JsonResponse Respuesta JSON que confirma la eliminación correcta del recordatorio.
     */
    public function destroy(Reminder $reminder): JsonResponse
    {
        $reminder->delete();

        return response()->json([
            'message' => 'Recordatorio eliminado correctamente.',
        ]);
    }
}
