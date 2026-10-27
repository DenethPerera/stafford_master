<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserPrivilege extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tbl_user_privilege';
    protected $primaryKey = 'idtbl_user_privilege';
    public $timestamps = false;
    
    protected $fillable = [
        'tbl_user_idtbl_user',
        'tbl_menu_list_idtbl_menu_list',
        'access_status',
        'add',
        'edit',
        'statuschange',
        'remove',
        'status',
        'approvestatus',
        'checkstatus',
        'updatedatetime',
    ];

    /**
     * Map the legacy foreign key to the new Breeze 'id' column
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'tbl_user_idtbl_user', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'tbl_menu_list_idtbl_menu_list', 'idtbl_menu_list');
    }

    /**
     * Activity Log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('userprivilages')
            ->logOnly([
                'idtbl_user_privilege',
                'tbl_user_idtbl_user',
                'tbl_menu_list_idtbl_menu_list',
                'access_status',
                'add',
                'edit',
                'statuschange',
                'remove',
                'status',
                'approvestatus',
                'checkstatus',
                'updatedatetime'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(
                fn($event) =>
                "User Privileges {$event}"
            );
    }
}
