<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|current_password',
            'password' => [
                'required', 'confirmed', 'different:current_password',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Ievadi pašreizējo paroli.',
            'current_password.current_password' => 'Pašreizējā parole ir nepareiza.',
            'password.required' => 'Ievadi jauno paroli.',
            'password.confirmed' => 'Paroles nesakrīt.',
            'password.different' => 'Jaunā parole nevar būt tāda pati kā pašreizējā.',
            'password.min' => 'Parolei jābūt vismaz 8 rakstzīmēm.',
        ];
    }
}
