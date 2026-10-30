<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Color extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'colors';

    protected $fillable = [
        'name',
        'code',
        'hex_code',
        'status',
    ];

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'code', 'hex_code', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
