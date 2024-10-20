<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollaboratorProject extends Model
{
    protected $fillable = ['collaborator_id', 'project_id'];

    protected $with = ['collaborator'];

    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(Collaborator::class);
    }
}
