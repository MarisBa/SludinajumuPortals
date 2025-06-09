<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdsFormUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Or implement permission logic
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            // Add other field rules here as needed
        ];
    }
}
