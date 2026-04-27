<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function advertisement($id)
    {
        $ad = Advertisement::with(['user', 'category', 'country', 'state', 'city'])
            ->findOrFail($id);

        if (Auth::id() !== $ad->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.advertisement', compact('ad'));

        return $pdf->download("sludinajums-{$ad->id}.pdf");
    }

    public function adsByCategory()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $categories = Category::with(['ads' => function ($q) {
            $q->where('published', 1)->with('user');
        }])->get();

        $totalAds = Advertisement::where('published', 1)->count();
        $generatedAt = now()->format('d.m.Y H:i');

        $pdf = Pdf::loadView('pdf.ads-by-category', compact('categories', 'totalAds', 'generatedAt'));

        return $pdf->download('sludinajumi-pa-kategorijam-' . now()->format('Y-m-d') . '.pdf');
    }

    public function usersList()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $users = User::orderBy('created_at', 'desc')->get();
        $totalUsers = $users->count();
        $blockedUsers = $users->where('is_blocked', true)->count();
        $adminUsers = $users->where('role', 'admin')->count();
        $generatedAt = now()->format('d.m.Y H:i');

        $pdf = Pdf::loadView('pdf.users-list', compact('users', 'totalUsers', 'blockedUsers', 'adminUsers', 'generatedAt'));

        return $pdf->download('lietotaji-' . now()->format('Y-m-d') . '.pdf');
    }
}
