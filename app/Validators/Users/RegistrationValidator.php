<?php

namespace Arc\Validators\Users;

use Arc\Models\User;
use Arc\Support\Validator;
use Illuminate\Validation\Rule;

class RegistrationValidator extends Validator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'regex:/^[a-z0-9\.]*$/', 'min:3', Rule::unique((new User)->getTable(), 'username')],
            'email'    => ['required', 'email', Rule::unique((new User)->getTable(), 'email')],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }
}