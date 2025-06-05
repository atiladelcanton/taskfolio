<?php

declare(strict_types=1);

namespace App\Domain\Board\Models;

use App\Domain\Project\Models\Project;
use App\Models\Task;
use Database\Factories\BoardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
    ];

    /**
     * @return BoardFactory
     */
    protected static function newFactory(): BoardFactory
    {
        return BoardFactory::new();
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
