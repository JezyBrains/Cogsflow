<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display the help and support view.
     */
    public function index()
    {
        return view('support.index');
    }
}
