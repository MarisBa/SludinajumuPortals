<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataExportController extends Controller
{
    public function request(Request $request)
    {
        $user = $request->user();

        // Build export data
        $data = [
            'profile' => $user->only(['name', 'username', 'email', 'phone', 'bio', 'location', 'address', 'created_at']),
            'advertisements' => Advertisement::where('user_id', $user->id)->get(['name', 'description', 'price', 'listing_location', 'created_at'])->toArray(),
            'notification_prefs' => $user->notification_prefs,
            'privacy_prefs' => $user->privacy_prefs,
            'exported_at' => now()->toISOString(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = "exports/user_{$user->id}_" . now()->format('Y-m-d_His') . '.json';
        Storage::put($filename, $json);

        return Storage::download($filename, 'mani_dati.json');
    }
}
