<?php

namespace Database\Factories;

use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleType>
 */
class VehicleTypeFactory extends Factory
{
    protected $model = VehicleType::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement([
                'Vehiculo',
                'Motocicleta',
                'Cuatrimoto',
                'Camioneta',
                'Camión',
            ]),
        ];
    }
}
