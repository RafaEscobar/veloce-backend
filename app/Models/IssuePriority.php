<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssuePriority extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
    ];

    public function pendingIssues(): HasMany
    {
        return $this->hasMany(PendingIssue::class, 'priority_id');
    }
}
