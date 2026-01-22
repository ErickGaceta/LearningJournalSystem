<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'fname' => ['required', 'string', 'max:191'],
            'mname' => ['nullable', 'string', 'max:191'],
            'lname' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'first_name' => $input['fname'],
            'middle_name' => $input['mname'] ?? null,
            'last_name' => $input['lname'],
            'email' => $input['email'],
            'password' => bcrypt($input['password'])
        ]);
    }
}
