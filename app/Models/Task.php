<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'collaborator_id',
        'task_id',
        'task_code',
        'priority',
        'type',
        'name',
        'description',
        'status',
        'total_hours',
        'evidences',
        'order',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

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

    public function sprint()
    {
        return $this->belongsToMany(TasksSprint::class, 'sprint_tasks', 'task_id', 'sprint_id');
    }

    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(Collaborator::class);
    }

    public function colaboratorsByProject()
    {
        return $this->hasMany(CollaboratorProject::class, 'project_id', 'project_id');
    }
}
