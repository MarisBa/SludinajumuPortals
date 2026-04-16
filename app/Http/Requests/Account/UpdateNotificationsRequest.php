<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'nullable|array',
            'email.*' => 'boolean',
            'push' => 'nullable|array',
            'push.*' => 'boolean',
            'sms' => 'nullable|array',
            'sms.*' => 'boolean',
        ];
    }
}
