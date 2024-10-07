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
        'task_id',
        'task_code',
        'priority',
        'type',
        'name',
        'description',
        'status',
        'total_hours',
        'evidences',
        'order'
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
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function taskFather(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'id','task_id')->whereNull('task_id');
    }
    public function sprint()
    {
        return $this->belongsToMany(TasksSprint::class,'sprint_tasks','task_id','sprint_id');
    }


}
