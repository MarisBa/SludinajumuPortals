<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'blocked_users' => User::where('is_blocked', true)->count(),
            'total_ads' => Advertisement::count(),
            'active_ads' => Advertisement::where('published', 1)->count(),
            'total_categories' => Category::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_ads' => Advertisement::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
