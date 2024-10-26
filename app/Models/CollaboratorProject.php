<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CollaboratorProject extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'collaborator_id',
        'project_id',
    ];


    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
