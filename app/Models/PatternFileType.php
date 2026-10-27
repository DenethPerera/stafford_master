<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PatternFileType extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'pattern_file_types';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

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

    /**
     * Style samples using this pattern file type.
     */
    public function samples(): HasMany
    {
        return $this->hasMany(StyleSample::class, 'pattern_file_type_id');
    }
}
