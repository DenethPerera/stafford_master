<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuomList extends Model
{
    use HasFactory;

    protected $table = 'muomlist';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'uom_code',
        'uom_name',
        'qrycancel',
    ];
}
