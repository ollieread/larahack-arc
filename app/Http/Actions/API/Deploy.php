<?php

namespace Arc\Http\Actions\API;

use Arc\Models\Message;
use Arc\Operations\Channels\GetChannel;
use Arc\Operations\Channels\PostToChannel;
use Arc\Support\Action;
use Exception;
use Illuminate\Http\Request;

class Deploy extends Action
{
    public function __invoke(Request $request)
    {
        try {
            (new PostToChannel)
                ->setChannel((new GetChannel)->setUuid('3e3fb544-f403-4888-84ce-c5b25cc395b4')->perform())
                ->setInput([
                    'type'     => Message::ACTION,
                    'action'   => 'deploy.' . $request->json('status'),
                    'metadata' => $request->getContent(),
                ])
                ->perform();
        } catch (Exception $exception) {
            report($exception);
        }
    }
}