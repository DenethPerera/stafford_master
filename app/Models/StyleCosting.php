<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleCosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'currency',
        'material_costs',
        'manufacturing_costs',
        'overhead_percent',
        'overhead_amount',
        'profit_percent',
        'profit_amount',
        'total_direct_cost',
        'total_cost',
        'target_price',
    ];

    protected $casts = [
        'style_id'            => 'integer',
        'material_costs'      => 'array',
        'manufacturing_costs' => 'array',
        'overhead_percent'    => 'float',
        'overhead_amount'     => 'float',
        'profit_percent'      => 'float',
        'profit_amount'       => 'float',
        'total_direct_cost'   => 'float',
        'total_cost'          => 'float',
        'target_price'        => 'float',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }
}
