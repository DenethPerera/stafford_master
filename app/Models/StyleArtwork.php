<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleArtwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'artwork_type_id',
        'artwork_name',
        'artwork_code',
        'type',
        'placement',
        'dimensions',
        'colors',
        'artwork_file',
    ];

    protected $casts = [
        'style_id'        => 'integer',
        'artwork_type_id' => 'integer',
        'colors'          => 'array',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function artworkType(): BelongsTo
    {
        return $this->belongsTo(ArtworkType::class, 'artwork_type_id');
    }
}
