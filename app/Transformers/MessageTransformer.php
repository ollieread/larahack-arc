<?php

namespace Arc\Transformers;

use Arc\Models\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message): array
    {
        $response = [
            'id'         => $message->uuid,
            'type'       => $message->type,
            'message'    => $message->message,
            'action'     => $message->action,
            'mentions'   => $message->mentions,
            'metadata'   => $message->metadata,
            'created_at' => $message->created_at->timestamp,
            'updated_at' => $message->updated_at->timestamp,
        ];

        if ($message->user_uuid) {
            $response['user'] = $message->user_uuid;
        } else if ($message->relationLoaded('user')) {
            $response['user'] = $message->user->uuid;
        }

        return $response;
    }
}