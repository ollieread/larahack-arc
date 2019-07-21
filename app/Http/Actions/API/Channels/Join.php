<?php

namespace Arc\Http\Actions\API\Channels;

use Arc\Operations\Channels\GetChannel;
use Arc\Operations\Users\JoinChannel;
use Arc\Services\Auth;
use Arc\Support\Action;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Join extends Action
{
    /**
     * @var \Arc\Services\Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(string $uuid)
    {
        $channel = (new GetChannel)
            ->setUuid($uuid)
            ->perform();

        if ($channel) {
            $user = $this->auth->user();
            $join = (new JoinChannel)
                ->setUser($user)
                ->setChannel($channel);

            if ($join->perform()) {
                return $this->response()->json(null, 201);
            }
        }

        throw new NotFoundHttpException;
    }
}