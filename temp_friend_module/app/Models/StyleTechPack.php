<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleTechPack extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'fitting',
        'stitching',
        'label_branding',
        'care_instructions',
        'packaging_notes',
        'drawcord_notes',
        'seam_notes',
        'thread_notes',
        'remarks',
        'pdf_path',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }
}
