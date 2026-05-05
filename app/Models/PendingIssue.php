<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'issue_status_id',
        'name',
        'description',
        'date',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function issueStatus(): BelongsTo
    {
        return $this->belongsTo(IssueStatus::class);
    }
}
