<?php

namespace Database\Seeders;

use App\Models\IssueStatus;
use Illuminate\Database\Seeder;

class IssueStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Solucionado',
            'Pendiente',
        ];

        foreach ($statuses as $status) {
            IssueStatus::query()->updateOrCreate(
                ['status' => $status],
                ['status' => $status],
            );
        }
    }
}
