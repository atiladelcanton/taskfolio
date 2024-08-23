<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'status',
        'total_hours',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
