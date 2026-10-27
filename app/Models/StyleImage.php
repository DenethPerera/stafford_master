<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'image_type',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'style_id'   => 'integer',
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }
        return asset('uploads/styles/' . $this->image_path);
    }
}
