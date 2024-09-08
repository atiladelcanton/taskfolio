<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'tasks_id',
        'task_code',
        'priority',
        'type',
        'name',
        'description',
        'status',
        'total_hours',
        'evidences'
    ];
    protected $casts = [
        'evidences' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'tasks_id');
    }


}
