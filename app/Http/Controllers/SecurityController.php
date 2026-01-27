<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Services\IdentityService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    protected $identity;
    protected $audit;

    public function __construct(IdentityService $identity, AuditService $audit)
    {
        $this->identity = $identity;
        $this->audit = $audit;
    }

    public function users()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::get();
        return view('security.users', compact('users', 'roles'));
    }

    public function audit()
    {
        $logs = \App\Models\AuditTrail::with('user')->latest()->paginate(20);
        return view('security.audit', compact('logs'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'required|array'
        ]);

        $this->identity->createUser($data, $request->roles ?? []);

        return redirect()->back()->with('success', 'User authorized successfully.');
    }

    public function editUser($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::get();
        return view('security.users_edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'roles' => 'required|array'
        ]);

        $this->identity->updateUser($user, $data, $request->roles);

        return redirect()->route('security.users')->with('success', 'Identity configuration updated.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Cannot self-destruct current administrative session.');
        }

        $this->identity->deleteUser($user);
        return redirect()->back()->with('success', 'Identity purged from directory.');
    }
}
