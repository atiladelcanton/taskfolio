<?php

declare(strict_types=1);

namespace App\Domain\Team\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property BelongsTo $owner
 */
class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['email', 'owner_id', 'user_id', 'invitation_code', 'expires_at', 'billing_type', 'billing_rate', 'cost_rate', 'status', 'role'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
