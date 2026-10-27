<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Usertype extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tbl_user_type';
    protected $primaryKey = 'idtbl_user_type';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'status',
        'updatedatetime'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'tbl_user_type_idtbl_user_type', 'idtbl_user_type');
    }

    /**
     * Activity Log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('usertype')
            ->logOnly([
                'idtbl_user_type',
                'type',
                'status',
                'updatedatetime'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(
                fn($event) =>
                "User Type {$event}"
            );
    }
}
