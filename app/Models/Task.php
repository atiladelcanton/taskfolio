<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Board\Models\Board;
use App\Domain\Sprint\Models\Sprint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_code',
        'sprint_id',
        'board_id',
        'type',
        'responsible_id',
        'subtask_id',
        'title',
        'description',
    ];

    /**
     * @return BelongsTo<Sprint, $this>
     */
    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    /**
     * @return BelongsTo<Board, $this>
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    /**
     * @return BelongsTo<Task, $this>
     */
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(self::class, 'subtask_id');
    }

    /**
     * @return HasMany<Task, $this>
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(self::class, 'subtask_id');
    }

    /**
     * @return HasMany<TimeTrack, $this>
     */
    public function timeTrackings(): HasMany
    {
        return $this->hasMany(TimeTrack::class);
    }

    /**
     * @return HasMany<TimeTrackUser, $this>
     */
    public function timeTrackUsers(): HasMany
    {
        return $this->hasMany(TimeTrackUser::class);
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return HasMany<Evidence, $this>
     */
    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }
}
