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
            $json = json_decode($request->input('payload'), true);
            (new PostToChannel)
                ->setChannel((new GetChannel)->setUuid('3e3fb544-f403-4888-84ce-c5b25cc395b4')->perform())
                ->setInput([
                    'type'     => Message::ACTION,
                    'action'   => 'deploy.' . $json['status'],
                    'metadata' => $json,
                ])
                ->perform();
        } catch (Exception $exception) {
            report($exception);
        }
    }
}