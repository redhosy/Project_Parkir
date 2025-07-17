<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard untuk customer
    public function index()
    {
        return view('customer.dashboard');
    }

    // Dashboard untuk admin
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}
