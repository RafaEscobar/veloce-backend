<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\VehicleTypeCollection;
use App\Models\VehicleType;

class VehicleTypeController extends Controller
{
    public function index(): VehicleTypeCollection
    {
        $vehicleTypes = VehicleType::paginate();

        return new VehicleTypeCollection($vehicleTypes);
    }
}
