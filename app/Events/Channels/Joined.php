<?php

namespace Arc\Events\Channels;

use Arc\Models\Channel;
use Arc\Models\User;
use Arc\Transformers\Channels\ChannelUserTransformer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Joined implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \Arc\Models\Channel
     */
    private $channel;

    /**
     * @var \Arc\Models\User
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @param \Arc\Models\Channel $channel
     * @param \Arc\Models\User    $user
     */
    public function __construct(Channel $channel, User $user)
    {
        $this->channel = $channel;
        $this->user    = $user;
    }

    public function broadcastAs(): string
    {
        return 'user.join';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel(sprintf('channel.%s', $this->channel->uuid));
    }

    public function broadcastWith(): array
    {
        return [
            'user' => (new ChannelUserTransformer)->transform($this->user),
        ];
    }
}
