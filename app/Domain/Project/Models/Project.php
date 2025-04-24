<?php

declare(strict_types=1);

namespace App\Domain\Project\Models;

use App\Domain\Board\Models\Board;
use App\Models\{Sprint, User};
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

/**
 * @property string $project_code
 * @property int $owner_id
 * @property string $name
 * @property string $description
 */
class Project extends Model
{
    /**
     * @use HasFactory<ProjectFactory>
     */
    use HasFactory;

    protected $fillable = ['project_code', 'owner_id', 'name', 'description'];

    /**
     * Get the owner of the project.
     *
     * @return BelongsTo<User, Project>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return BelongsToMany<User, Project>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_projects')->whereNull('user_projects.deleted_at')->withTimestamps();
    }

    /**
     * @return HasMany<Sprint, Project>
     */
    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class);
    }

    /**
     * @return HasMany<Board, $this>
     */
    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }
}
