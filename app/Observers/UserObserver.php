<?php

namespace Arc\Observers;

use Arc\Models\Channel;
use Arc\Models\User;
use Arc\Operations\Channels\GetChannels;
use Arc\Operations\Users\JoinChannel;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param \Arc\Models\User $user
     *
     * @return void
     */
    public function created(User $user): void
    {
        (new GetChannels)
            ->setDefault(true)
            ->perform()
            ->each(static function (Channel $channel) use ($user) {
                (new JoinChannel)->setUser($user)->setChannel($channel)->perform();
            });
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \Arc\Models\User $user
     *
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param \Arc\Models\User $user
     *
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param \Arc\Models\User $user
     *
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param \Arc\Models\User $user
     *
     * @return void
     */
    public function updated(User $user)
    {
        //
    }
}
