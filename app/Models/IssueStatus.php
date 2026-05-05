<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
    ];

    public function pendingIssues(): HasMany
    {
        return $this->hasMany(PendingIssue::class);
    }
}
