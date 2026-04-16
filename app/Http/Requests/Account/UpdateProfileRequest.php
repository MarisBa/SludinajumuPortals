<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'username' => [
                'nullable', 'string', 'min:3', 'max:30',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'bio' => 'nullable|string|max:160',
            'location' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vārds ir obligāts.',
            'username.regex' => 'Lietotājvārds var saturēt tikai mazos burtus, ciparus un _',
            'username.unique' => 'Šis lietotājvārds jau ir aizņemts.',
            'username.min' => 'Lietotājvārdam jābūt vismaz 3 rakstzīmēm.',
            'bio.max' => 'Bio nevar pārsniegt 160 rakstzīmes.',
            'image.max' => 'Attēls nevar pārsniegt 5MB.',
            'image.image' => 'Failam jābūt attēlam.',
        ];
    }
}
