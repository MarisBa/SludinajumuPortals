<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsernameController extends Controller
{
    public function check(Request $request): JsonResponse
    {
        $username = strtolower(trim($request->query('username', '')));

        if (strlen($username) < 3) {
            return response()->json(['available' => false, 'message' => 'Pārāk īss']);
        }

        if (!preg_match('/^[a-z0-9_]+$/', $username)) {
            return response()->json(['available' => false, 'message' => 'Neatļautas rakstzīmes']);
        }

        $taken = User::where('username', $username)
            ->where('id', '!=', $request->user()->id)
            ->exists();

        return response()->json([
            'available' => !$taken,
            'message' => $taken ? 'Aizņemts' : 'Pieejams',
        ]);
    }
}
