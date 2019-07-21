<?php

namespace Arc\Operations\Users;

use Arc\Events\Channels\Joined;
use Arc\Models\Channel;
use Arc\Models\Message;
use Arc\Models\User;
use Arc\Operations\Channels\PostToChannel;
use Arc\Support\Permissions\ChannelPermissions;

class JoinChannel
{
    /**
     * @var \Arc\Models\User
     */
    private $user;

    /**
     * @var \Arc\Models\Channel
     */
    private $channel;

    /**
     * @var int|null
     */
    private $permissions;

    public function perform(): bool
    {
        $presence = $this->user
                ->channels()
                ->where('uuid', '=', $this->channel->uuid)
                ->count() > 0;

        if (! $presence) {
            $this->user->channels()->save($this->channel, ['permissions' => $this->permissions ?? ChannelPermissions::DEFAULT_PERMISSIONS]);
            $this->user->channel_permissions = $this->permissions ?? ChannelPermissions::DEFAULT_PERMISSIONS;

            broadcast(new Joined($this->channel, $this->user));

            (new PostToChannel)
                ->setUser($this->user)
                ->setChannel($this->channel)
                ->setInput([
                    'type'     => Message::ACTION,
                    'action'   => 'join',
                    'metadata' => [
                        'automated' => true,
                    ],
                ])->perform();
        }

        return true;
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
     * @param \Arc\Models\User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}