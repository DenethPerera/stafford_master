<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DesignPosition extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'design_positions';

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'code', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
