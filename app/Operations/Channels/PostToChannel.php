<?php

namespace Arc\Operations\Channels;

use Arc\Events\Channels\Message as MessageEvent;
use Arc\Models\Channel;
use Arc\Models\Message;
use Arc\Models\User;
use Arc\Validators\Messages\CreateValidator;

class PostToChannel
{
    /**
     * @var \Arc\Models\User|null
     */
    private $user;

    /**
     * @var \Arc\Models\Channel
     */
    private $channel;

    /**
     * @var array
     */
    private $input;

    public function perform(): ?Message
    {
        CreateValidator::validate($this->input);

        $message = (new Message)->fill($this->input);

        if ($this->user) {
            $message->user()->associate($this->user);
        }

        if ($this->channel->messages()->save($message)) {
            broadcast(new MessageEvent($this->channel, $this->user, $message));
            return $message;
        }

        return null;
    }

    /**
     * @param \Arc\Models\Channel $channel
     *
     * @return $this
     */
    public function setChannel(Channel $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param array $input
     *
     * @return $this
     */
    public function setInput(array $input): self
    {
        $this->input = $input;
        return $this;
    }

    /**
     * @param \Arc\Models\User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user = null): self
    {
        $this->user = $user;
        return $this;
    }
}