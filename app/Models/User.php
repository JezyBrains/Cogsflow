<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class);
    }

    public function hasRole($roleSlug)
    {
        return $this->roles->contains(function ($role) use ($roleSlug) {
            return strtolower($role->slug) === strtolower($roleSlug);
        }) || $this->roles()->where('slug', strtolower($roleSlug))->exists();
    }

    public function hasPermission($permissionSlug)
    {
        return \App\Models\Permission::where('slug', $permissionSlug)
            ->whereHas('roles', function ($q) {
                $q->whereHas('users', function ($uq) {
                    $uq->where('users.id', $this->id);
                });
            })->exists();
    }
}
