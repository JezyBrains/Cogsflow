<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Services\IdentityService;
use App\Services\AuditService;
use Illuminate\Http\Request;

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
            'roles' => 'array'
        ]);

        $this->identity->createUser($data, $request->roles ?? []);

        return redirect()->back()->with('success', 'User authorized successfully.');
    }
}
