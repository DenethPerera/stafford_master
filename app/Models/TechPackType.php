<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TechPackType extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tech_pack_types';

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
     * Associated tech pack documents across styles.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StyleTechPackDocument::class, 'tech_pack_type_id');
    }
}
