<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IssuePriorityCollection;
use App\Models\IssuePriority;
use Illuminate\Http\Request;

class IssuePriorityController extends Controller
{
    /**
     * Listado de prioridades de problemas.
     */
    public function index(): IssuePriorityCollection
    {
        $priorities = IssuePriority::all();

        return new IssuePriorityCollection($priorities);
    }
}
