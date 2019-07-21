<?php

namespace Arc\Http\Actions\API\Users;

use Arc\Services\Auth;
use Arc\Support\Action;
use Arc\Transformers\UserTransformer;

class Me extends Action
{
    /**
     * @var \Arc\Services\Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke()
    {
        $user = $this->auth->user();

        return $this->transform($user, UserTransformer::class);
    }
}