<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Menampilkan halaman landing page.
     */
    public function index()
    {
        return view('welcome');
    }
}