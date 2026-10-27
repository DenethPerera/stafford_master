<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'user_id',
        'comment',
        'is_new',
    ];

    protected $casts = [
        'style_id' => 'integer',
        'user_id'  => 'integer',
        'is_new'   => 'boolean',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
