<?php

namespace Arc\Http\Actions\API\Users;

use Arc\Services\Auth;
use Arc\Support\Action;
use Arc\Transformers\TokenTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Login extends Action
{
    /**
     * @var \Arc\Services\Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(Request $request)
    {
        $input = $request->only(['login', 'password']);
        $token = $this->auth->login($input['login'], $input['password']);

        if ($token) {
            return $this->transform($token, TokenTransformer::class);
        }

        throw new BadRequestHttpException;
    }
}