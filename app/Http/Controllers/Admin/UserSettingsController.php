<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserSettingsController extends Controller
{
    /**
     * Display the user management page (manajemen pengguna).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        // Statistik pengguna
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

        // Daftar pengguna + pencarian
        $users = User::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.userSettings.index', compact(
            'users',
            'search',
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers'
        ));
    }
}
