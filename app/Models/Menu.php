<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Menu extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tbl_menu_list';
    protected $primaryKey = 'idtbl_menu_list';
    public $timestamps = false;

    protected $fillable = [
        'menu',
        'status'
    ];

    public function privileges()
    {
        return $this->hasMany(UserPrivilege::class, 'tbl_menu_list_idtbl_menu_list', 'idtbl_menu_list');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tbl_user_privilege', 'tbl_menu_list_idtbl_menu_list', 'tbl_user_idtbl_user');
    }

    /**
     * Activity Log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('menu')
            ->logOnly([
                'idtbl_menu_list',
                'menu',
                'status'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(
                fn($event) => "Menu {$event}"
            );
    }
}
