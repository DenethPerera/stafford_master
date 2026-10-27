<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'contact_person',
        'status',
        'notes',
    ];

    /**
     * Configure Spatie activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'code', 'email', 'phone', 'contact_person', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Styles belonging to this client in PLM.
     */
    public function styles(): HasMany
    {
        return $this->hasMany(Style::class, 'client_id');
    }

    /**
     * Check if this client has associated PLM styles.
     */
    public function hasDownstreamDependencies(): bool
    {
        return $this->styles()->exists();
    }
}
