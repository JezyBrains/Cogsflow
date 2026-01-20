<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class IdentityService
{
    protected $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    /**
     * Create a new user and assign roles.
     */
    public function createUser(array $data, array $roleSlugs = [])
    {
        return DB::transaction(function () use ($data, $roleSlugs) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if (!empty($roleSlugs)) {
                $roles = Role::whereIn('slug', $roleSlugs)->get();
                $user->roles()->attach($roles);
            }

            $this->audit->log('user_created', $user, null, $user->toArray());

            return $user;
        });
    }

    /**
     * Assign a role to a user.
     */
    public function assignRole(User $user, string $roleSlug)
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();
        $user->roles()->syncWithoutDetaching([$role->id]);

        $this->audit->log('role_assigned', $user, null, ['role' => $roleSlug]);
    }
}
