<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartonDimension extends Model
{
    use HasFactory;

    protected $table = 'tbl_carton_dimension';
    protected $primaryKey = 'idtbl_carton_dimension';

    public $timestamps = true;

    protected $fillable = [
        'styleid',
        'prodstyle_list_id',
        'tbl_style_size_desc',
        'pcs_per_carton',
        'pcs_per_varient',
        'carton_dimension_code',
        'carton_length',
        'carton_width',
        'carton_height',
        'item_weight',
        'carton_weight',
        'cbm_per_carton',
        'carton_volume',
        'ratio_pack_carton',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'pcs_per_carton'    => 'integer',
        'pcs_per_varient'   => 'integer',
        'carton_length'     => 'float',
        'carton_width'      => 'float',
        'carton_height'     => 'float',
        'item_weight'       => 'float',
        'carton_weight'     => 'float',
        'cbm_per_carton'    => 'float',
        'carton_volume'     => 'float',
        'ratio_pack_carton' => 'integer',
        'status'            => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'styleid', 'style_no');
    }

    /**
     * Calculate CBM: (Length * Width * Height) / 1,000,000 (cm to m3)
     */
    public static function calculateCbm($length, $width, $height): float
    {
        return round(($length * $width * $height) / 1000000, 3);
    }

    /**
     * Calculate Carton Size (Length + 2*(Width + Height) in inches)
     */
    public static function calculateCartonSize($length, $width, $height): float
    {
        $l_in = $length / 2.54;
        $w_in = $width / 2.54;
        $h_in = $height / 2.54;
        return round($l_in + (2 * ($w_in + $h_in)), 2);
    }
}

