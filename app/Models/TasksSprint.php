<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TasksSprint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sprint_id',
        'task_id',
    ];

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
