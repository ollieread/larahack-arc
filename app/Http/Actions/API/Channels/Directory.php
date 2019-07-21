<?php

namespace Arc\Http\Actions\API\Channels;

use Arc\Operations\Channels\GetChannels;
use Arc\Services\Auth;
use Arc\Support\Action;
use Arc\Transformers\ChannelTransformer;

class Directory extends Action
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
            ->perform();

        return $this->transform($channels, ChannelTransformer::class);
    }
}