<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FileType extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'file_types';

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
     * Style files using this file type.
     */
    public function styleFiles(): HasMany
    {
        return $this->hasMany(StyleFile::class, 'file_type_id');
    }
}
