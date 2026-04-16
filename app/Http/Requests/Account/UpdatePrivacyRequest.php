<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrivacyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_visibility' => 'nullable|in:public,registered,private',
            'show_phone' => 'nullable|boolean',
            'show_full_name' => 'nullable|boolean',
            'allow_messages' => 'nullable|boolean',
            'personalized_ads' => 'nullable|boolean',
        ];
    }
}
