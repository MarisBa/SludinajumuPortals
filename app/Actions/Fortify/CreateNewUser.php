<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'phone' => ['required', 'string', 'regex:/^\+?\d{8,15}$/', 'max:20'],
            'password' => $this->passwordRules(),
        ], [
            'name.required' => 'Vārds ir obligāts.',
            'email.required' => 'E-pasts ir obligāts.',
            'email.email' => 'Nederīgs e-pasta formāts.',
            'email.unique' => 'Šis e-pasts jau ir reģistrēts.',
            'phone.required' => 'Tālruņa numurs ir obligāts.',
            'phone.regex' => 'Tālruņa numuram jābūt formātā +37120000000.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
            'role' => 'user',
        ]);
    }
}
