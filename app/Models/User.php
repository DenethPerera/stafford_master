<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

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
        'factory_id',
        'mbranchlist_id',
        'lab_id',
    ];

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
                'tbl_user_type_idtbl_user_type',
                'factory_id',
                'lab_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(
                fn($event) => "User {$event}"
            );
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // CertTrack / Core System Relationships & Helpers
    // ─────────────────────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->hasRole('super-admin') || $this->hasRole('admin');
    }

    public function isFactory(): bool
    {
        return $this->hasRole('factory');
    }

    public function isLab(): bool
    {
        return $this->hasRole('lab');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Erav\EravBranch::class, 'mbranchlist_id', 'id');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function cpscTokens()
    {
        return $this->hasMany(CpscToken::class);
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // PLM Module Privilege & User Type Integration
    // ─────────────────────────────────────────────────────────────────────────────

    public function userType()
    {
        return $this->belongsTo(Usertype::class, 'tbl_user_type_idtbl_user_type', 'idtbl_user_type');
    }

    public function privileges()
    {
        return $this->hasMany(UserPrivilege::class, 'tbl_user_idtbl_user', 'id');
    }

    public function hasPrivilege($menuId, $action)
    {
        if ($this->hasAnyRole(['super_admin', 'super-admin', 'admin'])) {
            return true;
        }

        $privileges = $this->getCachedFullPrivileges();
        $privilege = $privileges->get($menuId);

        if (!$privilege) {
            return false;
        }

        return (bool) ($privilege->$action ?? false);
    }

    public function canAccessMenu($menuId)
    {
        if ($this->hasAnyRole(['super_admin', 'super-admin', 'admin'])) {
            return true;
        }

        $privileges = $this->getCachedPrivileges();
        return in_array($menuId, $privileges);
    }

    public function getMenuPrivileges($menuId)
    {
        $privileges = $this->getCachedFullPrivileges();
        return $privileges->get($menuId);
    }

    public function getCachedPrivileges()
    {
        return Cache::remember("user_privileges_{$this->id}", 3600, function () {
            if (!\Illuminate\Support\Facades\Schema::hasTable('tbl_user_privilege')) {
                return [];
            }
            return DB::table('tbl_user_privilege')
                ->where('tbl_user_idtbl_user', $this->id)
                ->where('access_status', 1)
                ->where('status', 1)
                ->pluck('tbl_menu_list_idtbl_menu_list')
                ->toArray();
        });
    }

    public function getCachedFullPrivileges()
    {
        return Cache::remember("user_full_privileges_{$this->id}", 3600, function () {
            if (!\Illuminate\Support\Facades\Schema::hasTable('tbl_user_privilege')) {
                return collect();
            }
            return DB::table('tbl_user_privilege')
                ->where('tbl_user_idtbl_user', $this->id)
                ->where('access_status', 1)
                ->where('status', 1)
                ->get()
                ->keyBy('tbl_menu_list_idtbl_menu_list');
        });
    }
}
