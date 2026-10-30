<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'file_type_id',
        'file_name',
        'file_type',
        'file_size',
        'folder',
        'description',
        'file_path',
        'uploaded_by',
    ];

    protected $casts = [
        'style_id'     => 'integer',
        'file_type_id' => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function fileType(): BelongsTo
    {
        return $this->belongsTo(FileType::class, 'file_type_id');
    }
}
