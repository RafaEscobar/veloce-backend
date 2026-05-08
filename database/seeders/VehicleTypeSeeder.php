<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Vehículo',
            'Motocicleta',
            'Cuatrimoto',
            'Camioneta',
            'Camión',
        ];

        foreach ($types as $type) {
            VehicleType::query()->updateOrCreate(
                ['type' => $type],
                ['type' => $type],
            );
        }
    }
}
