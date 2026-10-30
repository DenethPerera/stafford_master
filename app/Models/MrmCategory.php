<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MrmCategory extends Model
{
    use HasFactory;

    protected $table = 'mrmcategory';
    protected $primaryKey = 'catcode';
    public $timestamps = false;

    protected $fillable = [
        'catcode',
        'catname',
        'qrycancel',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MrmItem::class, 'catcode', 'catcode');
    }
}
