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
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'tasks_id');
    }

    public function evidencies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EvidenciesTask::class);
    }

    public function getStatusAttribute(): string
    {

        switch ($this->attributes['status']) {
            case 1:
                return 'Backlog';
                break;
            case 2:
                return 'Em andamento';
                break;
            case 3:
                return 'Validação';
                break;
            case 4:
                return 'Correção';
                break;
            case 5:
                return 'Concluído';
                break;
            default:
                return 'Backlog';
        }
    }
}
