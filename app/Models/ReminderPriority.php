<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReminderPriority extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
    ];

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }
}
