<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['client_id', 'name', 'description', 'hours_month', 'hourly_rate', 'active']);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
