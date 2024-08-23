<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Collaborator extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
        'user_id',
        'email',
        'hourly_rate',
        'pix',
        'bank_name',
    ];

    protected $with = ['user', 'projects'];

    protected $casts = [
        'hourly_rate' => 'float',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::deleted(function ($collaborator) {
            $collaborator->user?->delete();
            $collaborator->projects()?->delete();
        });

    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'collaborator_projects')->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'name', 'email', 'hourly_rate', 'pix', 'bank_name']);
    }
}
