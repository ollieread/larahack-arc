<?php

namespace Arc\Transformers\Channels;

use Arc\Models\User;
use League\Fractal\TransformerAbstract;

class ChannelUserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        return [
            'id'          => $user->uuid,
            'username'    => $user->username,
            'permissions' => $user->pivot->permissions,
            'created_at'  => $user->created_at->timestamp,
            'updated_at'  => $user->updated_at->timestamp,
        ];
    }
}