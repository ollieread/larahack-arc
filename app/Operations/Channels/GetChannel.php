<?php

namespace Arc\Operations\Channels;

use Arc\Models\Channel;
use Ramsey\Uuid\Uuid;

class GetChannel
{
    /**
     * @var \Ramsey\Uuid\Uuid|null
     */
    private $uuid;

    public function perform(): ?Channel
    {
        return Channel::query()->where('uuid', '=', $this->uuid)->first();
    }

    /**
     * @param \Ramsey\Uuid\Uuid|string $uuid
     *
     * @return $this
     */
    public function setUuid($uuid): self
    {
        $this->uuid = $uuid instanceof Uuid ? $uuid : Uuid::fromString($uuid);
        return $this;
    }
}