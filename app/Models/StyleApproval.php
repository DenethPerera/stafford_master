<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleApproval extends Model
{
    use HasFactory;

    protected $table = 'style_approvals';

    protected $fillable = [
        'style_id',
        'gate_name',
        'status',
        'approved_by',
        'approval_date',
        'remarks',
        'sort_order',
    ];

    protected $casts = [
        'approval_date' => 'datetime',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }
}
