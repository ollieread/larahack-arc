<?php

namespace Arc\Events\Channels;

use Arc\Models\Channel;
use Arc\Models\Message as MessageModel;
use Arc\Models\User;
use Arc\Transformers\MessageTransformer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \Illuminate\Broadcasting\Channel
     */
    private $channel;

    /**
     * @var \Arc\Models\User
     */
    private $user;

    /**
     * @var \Arc\Events\Channels\Message
     */
    private $message;

    /**
     * Create a new event instance.
     *
     * @param \Arc\Models\Channel $channel
     * @param \Arc\Models\User    $user
     * @param \Arc\Models\Message $message
     */
    public function __construct(Channel $channel, User $user, MessageModel $message)
    {
        $this->channel = $channel;
        $this->user    = $user;
        $this->message = $message;
    }

    public function broadcastAs(): string
    {
        return 'message.received';
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
            'message' => (new MessageTransformer)->transform($this->message),
        ];
    }
}
