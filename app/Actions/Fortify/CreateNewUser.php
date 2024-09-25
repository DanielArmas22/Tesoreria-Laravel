<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'idEstudiante' => ['required', 'integer', 'exists:estudiante,idEstudiante'], // Verificación de existencia
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'idEstudiante.exists' => 'El ID de estudiante no existe burro.',
            'terms.required' => 'Debe aceptar los términos y condiciones.'
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'idEstudiante' => $input['idEstudiante'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
