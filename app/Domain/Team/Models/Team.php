<?php

declare(strict_types=1);

namespace App\Domain\Team\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function ownerId(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
