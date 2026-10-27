<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'measurement_point',
        'spec_value',
        'tol_minus',
        'tol_plus',
        'xs',
        's',
        'm',
        'l',
        'xl',
        'xxl',
        '3xl',
        '4xl',
        '5xl',
        '6xl',
        'uom',
        'sort_order',
    ];

    protected $casts = [
        'style_id'   => 'integer',
        'spec_value' => 'float',
        'tol_minus'  => 'float',
        'tol_plus'   => 'float',
        'xs'         => 'float',
        's'          => 'float',
        'm'          => 'float',
        'l'          => 'float',
        'xl'         => 'float',
        'xxl'        => 'float',
        '3xl'        => 'float',
        '4xl'        => 'float',
        '5xl'        => 'float',
        '6xl'        => 'float',
        'sort_order' => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }
}
