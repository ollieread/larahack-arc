<?php

namespace Arc\Http\Actions\API\Users;

use Arc\Operations\Users\RegisterUser;
use Arc\Support\Action;
use Arc\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Register extends Action
{
    public function __invoke(Request $request)
    {
        $input = $request->only([
            'username',
            'password',
            'password_confirmation',
            'email',
        ]);

        $user = (new RegisterUser)
            ->setInput($input)
            ->perform();

        if ($user) {
            return $this->transform($user, UserTransformer::class);
        }

        throw new BadRequestHttpException;
    }
}