<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\IssueStatusCollection;
use App\Models\IssueStatus;

class IssueStatusController extends Controller
{
    public function index(): IssueStatusCollection
    {
        $issueStatuses = IssueStatus::all();

        return new IssueStatusCollection($issueStatuses);
    }
}
