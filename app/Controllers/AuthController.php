<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function authenticate()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please fill in all fields.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', $username)->get()->getRow();

        if ($user && password_verify($password, $user->password_hash)) {
            // Set session data
            $sessionData = [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            
            return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user->username . '!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }
}
