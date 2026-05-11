<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\VehicleStatusCollection;
use App\Models\VehicleStatus;

class VehicleStatusController extends Controller
{
    public function index(): VehicleStatusCollection
    {
        $vehicleStatuses = VehicleStatus::all();

        return new VehicleStatusCollection($vehicleStatuses);
    }
}
