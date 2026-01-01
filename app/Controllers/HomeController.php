<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    /**
     * Display the public landing page
     * 
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Disable CSP for landing page to allow external resources
        $this->response->removeHeader('Content-Security-Policy');
        $this->response->setHeader('Content-Security-Policy', "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; font-src * data:; img-src * data: blob:; connect-src *;");
        
        // Use default company information (can be enhanced later with SettingsModel)
        $data = [
            'company_name' => 'Nipo Agro',
            'company_email' => 'info@nipoagro.com',
            'company_phone' => '0714349614, 0713671675',
            'system_name' => 'Nipo Agro Management System',
        ];
        
        return view('home/landing_new', $data);
    }
    
    /**
     * Display about page
     * 
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function about()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Disable CSP for landing page to allow external resources
        $this->response->removeHeader('Content-Security-Policy');
        $this->response->setHeader('Content-Security-Policy', "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; font-src * data:; img-src * data: blob:; connect-src *;");
        
        return view('home/about');
    }
    
    /**
     * Display services page
     * 
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function services()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Disable CSP for landing page to allow external resources
        $this->response->removeHeader('Content-Security-Policy');
        $this->response->setHeader('Content-Security-Policy', "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; font-src * data:; img-src * data: blob:; connect-src *;");
        
        return view('home/services');
    }
    
    /**
     * Display products page
     * 
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function products()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Disable CSP for landing page to allow external resources
        $this->response->removeHeader('Content-Security-Policy');
        $this->response->setHeader('Content-Security-Policy', "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; font-src * data:; img-src * data: blob:; connect-src *;");
        
        return view('home/products');
    }
    
    /**
     * Display features page - redirect to landing page with anchor
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function features()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        return redirect()->to('/#features');
    }
    
    /**
     * Display contact page
     * 
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function contact()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Disable CSP for landing page to allow external resources
        $this->response->removeHeader('Content-Security-Policy');
        $this->response->setHeader('Content-Security-Policy', "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; font-src * data:; img-src * data: blob:; connect-src *;");
        
        return view('home/contact');
    }
    
    /**
     * Handle contact form submission
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function submitContact()
    {
        // Basic validation
        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'required|min_length[10]|max_length[20]',
            'email' => 'required|valid_email',
            'subject' => 'required|min_length[5]|max_length[200]',
            'message' => 'required|min_length[10]|max_length[1000]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Here you would typically save to database or send email
        // For now, just redirect with success message
        return redirect()->to('contact')->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
