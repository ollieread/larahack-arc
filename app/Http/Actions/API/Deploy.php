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
            parse_str($request->getContent(), $arr);
            $json = json_decode($arr['payload'] ?? null, true);
            (new PostToChannel)
                ->setChannel((new GetChannel)->setUuid('3e3fb544-f403-4888-84ce-c5b25cc395b4')->perform())
                ->setInput([
                    'type'     => Message::ACTION,
                    'action'   => 'deploy.' . $json['status'],
                    'metadata' => [
                        'start_revision' => $json['start_revision'],
                        'end_revision'   => $json['end_revision'],
                    ],
                ])
                ->perform();
        } catch (Exception $exception) {
            report($exception);
        }
    }
}