<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Size extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sizes';

    protected $fillable = [
        'name',
        'code',
        'sort_order',
        'status',
        'remark',
    ];

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'code', 'sort_order', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
