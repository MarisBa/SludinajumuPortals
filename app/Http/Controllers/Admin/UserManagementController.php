<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($filter = $request->input('filter')) {
            if ($filter === 'blocked') {
                $query->where('is_blocked', true);
            }
            if ($filter === 'admin') {
                $query->where('role', 'admin');
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function block(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Nevar bloķēt administratoru.');
        }

        $user->update(['is_blocked' => true]);

        return back()->with('message', "Lietotājs {$user->name} ir bloķēts.");
    }

    public function unblock(User $user)
    {
        $user->update(['is_blocked' => false]);

        return back()->with('message', "Lietotājs {$user->name} ir atbloķēts.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Nevar dzēst administratoru.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('message', "Lietotājs {$name} ir dzēsts.");
    }
}
