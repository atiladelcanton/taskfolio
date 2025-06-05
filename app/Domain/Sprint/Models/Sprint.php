<?php

declare(strict_types=1);

namespace App\Domain\Sprint\Models;

use App\Domain\Project\Models\Project;
use App\Domain\Sprint\Enums\SprintStatus;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'sprint_code',
        'project_id',
        'name',
        'date_start',
        'date_end',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SprintStatus::class,
            'date_start' => 'datetime',
            'date_end' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
