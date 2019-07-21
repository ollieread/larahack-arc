<?php

namespace Arc\Transformers;

use Arc\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        return [
            'id'         => $user->uuid,
            'username'   => $user->username,
            'created_at' => $user->created_at->timestamp,
            'updated_at' => $user->updated_at->timestamp,
        ];
    }
}