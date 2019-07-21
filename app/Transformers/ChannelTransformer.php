<?php

namespace Arc\Transformers;

use Arc\Models\Channel;
use Arc\Transformers\Channels\ChannelUserTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class ChannelTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'users',
    ];

    public function includeUsers(Channel $channel): Collection
    {
        return $this->collection($channel->users, new ChannelUserTransformer);
    }

    public function transform(Channel $channel): array
    {
        return [
            'id'          => $channel->uuid,
            'name'        => $channel->name,
            'description' => $channel->description,
            'private'     => $channel->private,
            'active'      => $channel->active,
            'created_at'  => $channel->created_at,
            'updated_at'  => $channel->updated_at,
        ];
    }
}