<?php

namespace App\Domain\Invitation\Models;

use App\Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamInvitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['email', 'team_id', 'invitation_code', 'expires_at', 'billing_type', 'billing_rate','cost_rate', 'status'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    protected function casts(): array
    {
        return ['expires_at' => 'timestamp',];
    }
}
