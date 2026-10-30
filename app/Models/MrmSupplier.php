<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MrmSupplier extends Model
{
    use HasFactory;

    protected $table = 'mrmsupplier';
    protected $primaryKey = 'supplierno';
    public $timestamps = false;

    protected $fillable = [
        'supplierno',
        'supregcode',
        'supname',
        'supaddr',
        'supcountry',
        'sup_contact_name',
        'suptpno',
        'supemail',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MrmItem::class, 'supplierno', 'supplierno');
    }
}
