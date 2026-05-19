<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StorePendingIssueRequest;
use App\Http\Requests\UpdatePendingIssueRequest;
use App\Http\Resources\PendingIssueCollection;
use App\Http\Resources\PendingIssueResource;
use App\Models\PendingIssue;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para la gestión de problemas pendientes de los vehículos de la API.
 *
 * Proporciona métodos para listar, crear, actualizar y eliminar registros de problemas pendientes
 * asociados a los vehículos del usuario autenticado.
 *
 * @group Problemas pendientes
 */
class PendingIssueController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PendingIssue::class, 'pending_issue');
    }

    /**
     * Listado de problemas pendientes del usuario autenticado.
     *
     * @authenticated
     * Recupera un listado paginado de todos los problemas pendientes registrados
     * para los vehículos del usuario autenticado, incluyendo información del estado y la prioridad del problema,
     * ordenados de forma descendente por fecha de creación.
     *
     * @param Request $request Objeto de petición actual.
     * @return PendingIssueCollection Colección paginada de problemas pendientes.
     */
    public function index(Request $request): PendingIssueCollection
    {
        $issues = PendingIssue::whereIn('vehicle_id', $request->user()->vehicles()->pluck('id'))
            ->with(['issueStatus', 'issuePriority'])
            ->latest()
            ->paginate();

        return new PendingIssueCollection($issues);
    }

    /**
     * Almacena un nuevo problema pendiente.
     *
     * @authenticated
     * Crea un nuevo registro de problema pendiente asociado a un vehículo específico del usuario.
     * Verifica que el vehículo pertenezca al usuario antes de proceder a la creación.
     *
     * @bodyParam vehicle_id int required ID del vehículo al que pertenece el problema pendiente. Example: 1
     * @bodyParam issue_status_id int required ID del estado actual del problema pendiente. Example: 1
     * @bodyParam name string required Título o nombre corto del problema pendiente. Example: Ruido en la suspensión delantera
     * @bodyParam description string Descripción detallada del problema o fallo detectado. Example: Se escucha un crujido metálico al pasar baches.
     * @bodyParam date string required Fecha en la que se reporta el problema en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam priority_id int required ID del nivel de prioridad asignado al problema. Example: 2
     *
     * @param StorePendingIssueRequest $request Objeto de petición con los datos de problema pendiente validados.
     * @return PendingIssueResource Recurso del problema pendiente creado.
     */
    public function store(StorePendingIssueRequest $request): PendingIssueResource
    {
        $validated = $request->validated();

        // Verificar que el vehículo pertenezca al usuario autenticado
        $request->user()->vehicles()->findOrFail($validated['vehicle_id']);

        $issue = PendingIssue::create($validated)->load(['issuePriority', 'issueStatus']);

        return new PendingIssueResource($issue);
    }

    /**
     * Actualiza un problema pendiente existente.
     *
     * @authenticated
     * Modifica los datos de un problema pendiente previamente registrado.
     * Si se intenta cambiar el vehículo, verifica que el nuevo vehículo pertenezca al usuario autenticado.
     *
     * @urlParam id integer required ID del registro de problema pendiente. Example: 1
     *
     * @bodyParam vehicle_id int ID del vehículo al que pertenece el problema pendiente. Example: 1
     * @bodyParam issue_status_id int ID del estado actual del problema pendiente. Example: 2
     * @bodyParam name string Título o nombre corto del problema pendiente. Example: Ruido en la suspensión delantera
     * @bodyParam description string Descripción detallada del problema o fallo detectado. Example: Se escucha un crujido metálico al pasar baches.
     * @bodyParam date string Fecha en la que se reporta el problema en formato AAAA-MM-DD. Example: 2026-05-19
     * @bodyParam priority_id int ID del nivel de prioridad asignado al problema. Example: 3
     *
     * @param UpdatePendingIssueRequest $request Objeto de petición con los datos a actualizar.
     * @param PendingIssue $pendingIssue Modelo del problema pendiente a actualizar.
     * @return PendingIssueResource Recurso del problema pendiente modificado.
     */
    public function update(UpdatePendingIssueRequest $request, PendingIssue $pendingIssue): PendingIssueResource
    {
        $validated = $request->validated();

        if (isset($validated['vehicle_id'])) {
            $request->user()->vehicles()->findOrFail($validated['vehicle_id']);
        }

        $pendingIssue->update($validated);

        return new PendingIssueResource($pendingIssue);
    }

    /**
     * Elimina un problema pendiente.
     *
     * @authenticated
     * Elimina de la base de datos el registro de problema pendiente especificado.
     * La autorización del recurso se realiza automáticamente a través de la Policy vinculada.
     *
     * @urlParam id integer required ID del registro de problema pendiente. Example: 1
     *
     * @param PendingIssue $pendingIssue Modelo del problema pendiente a eliminar.
     * @return JsonResponse Respuesta JSON que confirma la eliminación correcta del problema pendiente.
     */
    public function destroy(PendingIssue $pendingIssue): JsonResponse
    {
        $pendingIssue->delete();

        return response()->json([
            'message' => 'Problema pendiente eliminado correctamente.',
        ]);
    }
}
