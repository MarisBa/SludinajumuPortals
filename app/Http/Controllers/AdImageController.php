<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class AdImageController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/private/public/category/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Image not found');
        }

        return response()->file($path);
    }
}

