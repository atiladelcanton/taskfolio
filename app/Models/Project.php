<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['client_id', 'name', 'description', 'hours_month', 'hourly_rate', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'hours_month' => 'integer',
        'hourly_rate' => 'float',
    ];

    protected $with = ['client'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['client_id', 'name', 'description', 'hours_month', 'hourly_rate', 'active']);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function collaborators()
    {
        return $this->belongsToMany(Collaborator::class,'collaborator_projects')->withTimestamps();
    }
}
