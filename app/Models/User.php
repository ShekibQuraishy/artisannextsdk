<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'fullName',
        'email',
        'password',
        'avatar',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'createdAt',
        'role',
        'permissions'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    // Get user's primary role
    public function getRoleAttribute()
    {
        return $this->roles->first()?->name ?? 'RestrictedUser';
    }

    // Get user's permissions
    public function getPermissionsAttribute()
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    public function hasPermissionTo($permission): bool
    {
        return $this->hasRole('Super Admin') || parent::hasPermissionTo($permission);
    }

    public function getAllPermissions()
    {
        if ($this->hasRole('Super Admin')) {
            return Permission::all();
        }
        return parent::getAllPermissions();
    }
}
