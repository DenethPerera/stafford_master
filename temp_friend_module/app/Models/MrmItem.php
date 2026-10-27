<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MrmItem extends Model
{
    use HasFactory;

    protected $table = 'mrmitem';
    protected $primaryKey = 'itemno';
    public $timestamps = false;

    protected $fillable = [
        'itemcode',
        'itemdesc',
        'item_uom',
        'catcode',
        'prod_line',
        'item_color',
        'avgprice',
        'avg_moq',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MrmCategory::class, 'catcode', 'catcode');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(MrmSupplier::class, 'supplierno', 'supplierno');
    }
}
