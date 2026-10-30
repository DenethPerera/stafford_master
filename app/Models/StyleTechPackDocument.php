<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleTechPackDocument extends Model
{
    use HasFactory;

    protected $table = 'style_tech_pack_documents';

    protected $fillable = [
        'style_id',
        'tech_pack_type_id',
        'type_name',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'style_id'          => 'integer',
        'tech_pack_type_id' => 'integer',
        'uploaded_by'       => 'integer',
    ];

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function techPackType(): BelongsTo
    {
        return $this->belongsTo(TechPackType::class, 'tech_pack_type_id');
    }

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
