<?php

namespace Arc\Http\Actions\API\Channels;

use Arc\Models\Message;
use Arc\Operations\Channels\GetChannel;
use Arc\Operations\Channels\PostToChannel;
use Arc\Services\Auth;
use Arc\Support\Action;
use Illuminate\Http\Request;

class Post extends Action
{
    /**
     * @var \Arc\Services\Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(string $channelUuid, Request $request)
    {
        $input         = $request->only(['message']);
        $input['type'] = Message::TEXT;

        $user    = $this->auth->user();
        $channel = (new GetChannel)
            ->setUuid($channelUuid)
            ->perform();

        if ($channel) {
            $message = (new PostToChannel)
                ->setUser($user)
                ->setChannel($channel)
                ->setInput($input)
                ->perform();

            if ($message) {
                return $this->response()->json(null, 200);
            }
        }
    }
}