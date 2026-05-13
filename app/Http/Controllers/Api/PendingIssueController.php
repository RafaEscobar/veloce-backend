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

class PendingIssueController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PendingIssue::class, 'pending_issue');
    }

    /**
     * Listado de problemas pendientes del usuario autenticado.
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
     */
    public function destroy(PendingIssue $pendingIssue): JsonResponse
    {
        $pendingIssue->delete();

        return response()->json([
            'message' => 'Problema pendiente eliminado correctamente.',
        ]);
    }
}
