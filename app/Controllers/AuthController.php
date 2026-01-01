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
            // Handle AJAX requests
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Please fill in all fields.'
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill in all fields.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        try {
            $db = \Config\Database::connect();
            $query = $db->table('users')->where('username', $username)->get();
            
            if ($query === false) {
                throw new \Exception('Database tables not initialized yet');
            }
            
            $user = $query->getRow();
        } catch (\Exception $e) {
            // Database not ready yet
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database is being initialized. Please wait a moment and try again.'
                ]);
            }
            return redirect()->back()->with('error', 'Database is being initialized. Please wait a moment and try again.');
        }

        if ($user && password_verify($password, $user->password_hash)) {
            // Set session data
            $sessionData = [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role ?? 'standard_user',
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            
            // Handle AJAX requests
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Authentication successful',
                    'redirect' => site_url('dashboard')
                ]);
            }
            
            return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user->username . '!');
        } else {
            // Handle AJAX requests
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Invalid username or password.'
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }
}
