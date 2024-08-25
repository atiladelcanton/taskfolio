<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenciesTask extends Model
{
    protected $table = 'evidence_tasks';

    protected $fillable = [
        'task_id',
        'filename',
    ];
}
