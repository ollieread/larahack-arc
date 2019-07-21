<?php

namespace Arc\Http\Actions\API\Users;

use Arc\Operations\Channels\GetChannels;
use Arc\Services\Auth;
use Arc\Support\Action;
use Arc\Transformers\ChannelTransformer;

class Channels extends Action
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
        $channels = (new GetChannels)
            ->setUser($this->auth->user())
            ->setUserOnly(true)
            ->setIncludePrivate(true)
            ->setWithMessages(true)
            ->perform();

        return $this->transform($channels, ChannelTransformer::class);
    }
}