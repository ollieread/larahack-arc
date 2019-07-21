<?php

namespace Arc\Operations\Users;

use Arc\Models\User;
use Arc\Validators\Users\RegistrationValidator;
use Illuminate\Support\Arr;

class RegisterUser
{
    /**
     * @var array
     */
    private $input;

    public function perform(): ?User
    {
        RegistrationValidator::validate($this->input);

        $user = (new User)->fill(Arr::except($this->input, ['password_confirmation']));

        if ($user->save()) {
            return $user;
        }

        return null;
    }

    /**
     * @param array $input
     *
     * @return \Arc\Operations\Users\RegisterUser
     */
    public function setInput(array $input): self
    {
        $this->input = $input;

        return $this;
    }
}