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
     * Update an existing user and sync roles.
     */
    public function updateUser(User $user, array $data, array $roleSlugs = [])
    {
        return DB::transaction(function () use ($user, $data, $roleSlugs) {
            $oldValues = $user->toArray();
            $user->name = $data['name'];
            $user->email = $data['email'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            if (!empty($roleSlugs)) {
                $roles = Role::whereIn('slug', $roleSlugs)->get();
                $user->roles()->sync($roles);
            }

            $this->audit->log('user_updated', $user, $oldValues, $data);

            return $user;
        });
    }

    /**
     * Delete a user record.
     */
    public function deleteUser(User $user)
    {
        return DB::transaction(function () use ($user) {
            $oldValues = $user->toArray();
            $user->roles()->detach();
            $user->delete();

            $this->audit->log('user_deleted', null, $oldValues, ['deleted_id' => $user->id]);
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
