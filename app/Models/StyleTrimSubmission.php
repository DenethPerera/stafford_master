<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleTrimSubmission extends Model
{
    use HasFactory;

    protected $table = 'style_trim_submissions';

    protected $fillable = [
        'style_id',
        'trim_type',
        'item_name',
        'supplier',
        'placement',
        'color',
        'submission_stage',
        'sent_date',
        'status',
        'approval_date',
        'approved_by',
        'remarks',
        'attachment',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'style_id'      => 'integer',
        'sent_date'     => 'date',
        'approval_date' => 'date',
        'sort_order'    => 'integer',
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment) {
            return null;
        }

        if (str_starts_with($this->attachment, 'http')) {
            return $this->attachment;
        }

        return asset('uploads/styles/trims/' . $this->attachment);
    }
}
