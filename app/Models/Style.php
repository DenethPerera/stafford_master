<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Style extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'styles';

    protected $fillable = [
        'style_no',
        'plm_no',
        'design_no',
        'style_name',
        'client_id',
        'season',
        'program',
        'category',
        'gender',
        'size_range',
        'sizes',
        'hts_code',
        'hts_code_ca',
        'unit',
        'content',
        'gsm',
        'construction',
        'treatment',
        'gauge',
        'sleeve',
        'hood',
        'pocket',
        'description',
        'tags',
        'packing_instructions',
        'special_instructions',
        'ship_to',
        'country',
        'port',
        'target_ship_date',
        'target_approval_date',
        'target_production_date',
        'development_start_date',
        'revision_no',
        'status',
        'overall_progress',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'client_id'              => 'integer',
        'created_by'             => 'integer',
        'updated_by'             => 'integer',
        'tags'                   => 'array',
        'sizes'                  => 'array',
        'target_ship_date'       => 'date',
        'target_approval_date'   => 'date',
        'target_production_date' => 'date',
        'development_start_date' => 'date',
        'overall_progress'       => 'integer',
    ];

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['style_no', 'style_name', 'client_id', 'season', 'status', 'revision_no'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(StyleImage::class, 'style_id')->orderBy('sort_order');
    }

    public function techPack(): HasOne
    {
        return $this->hasOne(StyleTechPack::class, 'style_id');
    }

    public function techPackDocuments(): HasMany
    {
        return $this->hasMany(StyleTechPackDocument::class, 'style_id')->orderBy('id', 'desc');
    }

    public function bomItems(): HasMany
    {
        return $this->hasMany(StyleBomItem::class, 'style_id')->orderBy('sort_order');
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(StyleMeasurement::class, 'style_id')->orderBy('sort_order');
    }

    public function colorways(): HasMany
    {
        return $this->hasMany(StyleColorway::class, 'style_id');
    }

    public function samples(): HasMany
    {
        return $this->hasMany(StyleSample::class, 'style_id')->orderBy('sort_order');
    }

    public function artworks(): HasMany
    {
        return $this->hasMany(StyleArtwork::class, 'style_id');
    }

    public function costing(): HasOne
    {
        return $this->hasOne(StyleCosting::class, 'style_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(StyleFile::class, 'style_id')->latest();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(StyleComment::class, 'style_id')->latest();
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(StyleRevision::class, 'style_id')->latest();
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(StyleApproval::class, 'style_id')->orderBy('sort_order');
    }

    public function trimSubmissions(): HasMany
    {
        return $this->hasMany(StyleTrimSubmission::class, 'style_id')->orderBy('sort_order')->latest('id');
    }

    public function cartonDimensions(): HasMany
    {
        return $this->hasMany(CartonDimension::class, 'styleid', 'style_no');
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $primary = $this->images->where('is_primary', true)->first() ?: $this->images->first();
        if ($primary) {
            return asset('uploads/styles/' . $primary->image_path);
        }
        return null;
    }
}

