<?php

namespace Database\Seeders;

use App\Models\ReminderPriority;
use Illuminate\Database\Seeder;

class ReminderPrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            'Crítica',
            'Alta',
            'Media',
            'Baja',
            'Opcional',
        ];

        foreach ($priorities as $priority) {
            ReminderPriority::query()->updateOrCreate(
                ['priority' => $priority],
                ['priority' => $priority],
            );
        }
    }
}
