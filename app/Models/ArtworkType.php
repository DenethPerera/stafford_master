<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ArtworkType extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'artwork_types';

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
     * Associated artworks across styles.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(StyleArtwork::class, 'artwork_type_id');
    }
}
