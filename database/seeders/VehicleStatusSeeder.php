<?php

namespace Database\Seeders;

use App\Models\VehicleStatus;
use Illuminate\Database\Seeder;

class VehicleStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Activo',
            'Vendido',
            'Suspendido',
        ];

        foreach ($statuses as $status) {
            VehicleStatus::query()->updateOrCreate(
                ['status' => $status],
                ['status' => $status],
            );
        }
    }
}
