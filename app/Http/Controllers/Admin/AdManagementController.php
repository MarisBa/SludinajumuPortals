<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use Illuminate\Http\Request;

class AdManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Advertisement::with(['user', 'category']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            $query->where('published', $status === 'published' ? 1 : 0);
        }

        $ads = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::all();

        return view('admin.ads.index', compact('ads', 'categories'));
    }

    public function destroy(Advertisement $ad)
    {
        $ad->delete();

        return back()->with('message', 'Sludinājums dzēsts.');
    }

    public function togglePublished(Advertisement $ad)
    {
        $ad->update(['published' => $ad->published ? 0 : 1]);
        $status = $ad->published ? 'publicēts' : 'paslēpts';

        return back()->with('message', "Sludinājums {$status}.");
    }
}
