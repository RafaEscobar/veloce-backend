<?php

namespace Database\Seeders;

use App\Models\IssuePriority;
use Illuminate\Database\Seeder;

class IssuePrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            'Baja',
            'Media',
            'Alta',
            'Crítica',
        ];

        foreach ($priorities as $priority) {
            IssuePriority::query()->updateOrCreate(
                ['priority' => $priority],
                ['priority' => $priority],
            );
        }
    }
}
