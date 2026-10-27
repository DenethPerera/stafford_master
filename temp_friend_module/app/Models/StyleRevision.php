<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'revision_no',
        'status',
        'changes_summary',
        'created_by',
    ];

    protected $casts = [
        'style_id'   => 'integer',
        'created_by' => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
