<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SampleType extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sample_types';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    /**
     * Samples associated with this sample type.
     */
    public function samples(): HasMany
    {
        return $this->hasMany(StyleSample::class, 'sample_type_id');
    }

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'code', 'description', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
