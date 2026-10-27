<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'status',
        'imagepath',
        'tbl_user_type_idtbl_user_type',
        'updatedatetime',
    ];

    /**
     * Get the user type that this user belongs to.
     */
    public function userType()
    {
        return $this->belongsTo(Usertype::class, 'tbl_user_type_idtbl_user_type', 'idtbl_user_type');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Activity Log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user')
            ->logOnly([
                'name',
                'username',
                'email',
                'status',
                'tbl_user_type_idtbl_user_type'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(
                fn($event) => "User {$event}"
            );
    }

    // ==========================================
    // PRIVILEGE SYSTEM INTEGRATION
    // ==========================================

    /**
     * Get the user's privileges.
     */
    public function privileges()
    {
        return $this->hasMany(UserPrivilege::class, 'tbl_user_idtbl_user', 'id');
    }

    public function hasPrivilege($menuId, $action)
    {
        if ($this->hasAnyRole(['super-admin', 'admin'])) {
            return true;
        }

        $privileges = $this->getCachedFullPrivileges();
        $privilege = $privileges->get($menuId);

        if (!$privilege) {
            return false;
        }

        return (bool) $privilege->$action;
    }

    /**
     * Check if user has any access to a menu
     */
    public function canAccessMenu($menuId)
    {
        if ($this->hasAnyRole(['super-admin', 'admin'])) {
            return true;
        }

        $privileges = $this->getCachedPrivileges();
        return in_array($menuId, $privileges);
    }

    /**
     * Get all privileges for a specific menu
     */
    public function getMenuPrivileges($menuId)
    {
        $privileges = $this->getCachedFullPrivileges();
        return $privileges->get($menuId);
    }

    /**
     * Cache the basic menu IDs the user can access
     * Uses Query Builder for performance
     */
    public function getCachedPrivileges()
    {
        return Cache::remember("user_privileges_{$this->id}", 3600, function () {
            return DB::table('tbl_user_privilege')
                ->where('tbl_user_idtbl_user', $this->id)
                ->where('access_status', 1)
                ->where('status', 1)
                ->pluck('tbl_menu_list_idtbl_menu_list')
                ->toArray();
        });
    }

    /**
     * Cache the full privilege objects for the user
     * Uses Query Builder for performance
     */
    public function getCachedFullPrivileges()
    {
        return Cache::remember("user_full_privileges_{$this->id}", 3600, function () {
            return DB::table('tbl_user_privilege')
                ->where('tbl_user_idtbl_user', $this->id)
                ->where('access_status', 1)
                ->where('status', 1)
                ->get()
                ->keyBy('tbl_menu_list_idtbl_menu_list');
        });
    }
}
