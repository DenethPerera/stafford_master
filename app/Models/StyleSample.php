<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'sample_type_id',
        'season',
        'sample_name',
        'sample_code',
        'tag_no',
        'sent_date',
        'waybill_no',
        'status',
        'submitted_by',
        'approval_date',
        'remarks',
        'qc_report',
        'cost_sheet',
        'comment_sheet',
        'pattern_file_type_id',
        'pattern_file',
        'pattern_uploaded_at',
        'pattern_notes',
        'sample_image',
        'sort_order',
    ];

    protected $casts = [
        'style_id'             => 'integer',
        'sample_type_id'       => 'integer',
        'pattern_file_type_id' => 'integer',
        'sent_date'            => 'date',
        'approval_date'        => 'date',
        'pattern_uploaded_at'  => 'datetime',
        'sort_order'           => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function sampleType(): BelongsTo
    {
        return $this->belongsTo(SampleType::class, 'sample_type_id');
    }

    public function patternFileType(): BelongsTo
    {
        return $this->belongsTo(PatternFileType::class, 'pattern_file_type_id');
    }
}
