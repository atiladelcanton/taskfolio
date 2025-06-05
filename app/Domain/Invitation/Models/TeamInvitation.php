<?php

declare(strict_types=1);

namespace App\Domain\Invitation\Models;

use App\Domain\Team\Models\Team;
use Database\Factories\TeamInvitationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $email
 * @property int $team_id
 * @property string $invitation_code
 * @property BelongsTo $team
 */
class TeamInvitation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['email', 'team_id', 'invitation_code', 'expires_at', 'billing_type', 'billing_rate', 'cost_rate', 'status', 'role'];

    /**
     * @return TeamInvitationFactory
     */
    protected static function newFactory(): TeamInvitationFactory
    {
        return TeamInvitationFactory::new();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    protected function casts(): array
    {
        return ['expires_at' => 'timestamp'];
    }
}
