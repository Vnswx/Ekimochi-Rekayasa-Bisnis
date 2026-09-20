<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index(): View
    {
        return view('dashboard');
    }

    /**
     * Show the admin dashboard.
     */
    public function adminDashboard(): View
    {
        return view('admin.dashboard');
    }
}
