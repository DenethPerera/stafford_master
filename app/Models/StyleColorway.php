<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleColorway extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'color_name',
        'pantone_code',
        'lab_op_ref',
        'swatch_color_hex',
        'swatch_image',
        'status',
    ];

    protected $casts = [
        'style_id' => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }
}
