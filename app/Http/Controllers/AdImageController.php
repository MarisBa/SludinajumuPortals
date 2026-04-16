<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class AdImageController extends Controller
{
    public function show($filename)
    {
        // Ja datubāzē ir pilns URL (Unsplash utt.) — redirect uz to
        $ad = \App\Models\Advertisement::where('feature_image', 'like', '%' . $filename . '%')
            ->orWhere('feature_image', 'like', 'http%')
            ->first();

        if ($ad && str_starts_with($ad->feature_image, 'http')) {
            return redirect($ad->feature_image);
        }

        // Lokālais fails
        $path = storage_path('app/private/public/category/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Image not found');
        }

        return response()->file($path);
    }
}
