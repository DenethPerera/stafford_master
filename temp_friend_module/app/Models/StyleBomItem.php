<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleBomItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'component_type',
        'item_description',
        'supplier',
        'placement',
        'color',
        'qty_per_size',
        'uom',
        'unit_cost',
        'total_cost',
        'sort_order',
    ];

    protected $casts = [
        'style_id'     => 'integer',
        'qty_per_size' => 'float',
        'unit_cost'    => 'float',
        'total_cost'   => 'float',
        'sort_order'   => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }
}
