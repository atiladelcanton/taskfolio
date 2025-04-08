<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_code',
        'owner_id',
        'name',
        'description',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User, \App\Models\Project>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_projects');
    }
    /**
     * @return HasMany<Sprint, $this>
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
