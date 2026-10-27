<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Scale extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'scales';

    protected $fillable = [
        'name',
        'percentage_value',
        'status',
    ];

    protected $appends = [
        'formatted_name',
    ];

    /**
     * Get clean formatted scale name without duplication.
     */
    public function getFormattedNameAttribute(): string
    {
        $name = trim($this->name ?? '');

        if (empty($name)) {
            return $this->percentage_value !== null ? number_format($this->percentage_value, 0) . '%' : '—';
        }

        // If name already contains '%', return as is (e.g. '100%', '90%')
        if (str_contains($name, '%')) {
            return $name;
        }

        // If name is numeric (e.g. '100'), append '%'
        if (is_numeric($name)) {
            return $name . '%';
        }

        // If percentage_value exists and not already part of name
        if ($this->percentage_value !== null) {
            $pctValStr = (string) (int) $this->percentage_value;
            if (str_contains($name, $pctValStr)) {
                return $name . '%';
            }
            return "{$name} (" . number_format($this->percentage_value, 0) . "%)";
        }

        return $name;
    }

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['name', 'percentage_value', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
