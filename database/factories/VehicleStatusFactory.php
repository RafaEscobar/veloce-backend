<?php

namespace Database\Factories;

use App\Models\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleStatus>
 */
class VehicleStatusFactory extends Factory
{
    protected $model = VehicleStatus::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement([
                'Activo',
                'vendido',
                'suspendido',
            ]),
        ];
    }
}
