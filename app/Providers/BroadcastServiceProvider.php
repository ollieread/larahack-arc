<?php

namespace Arc\Providers;

use Arc\Models\User;
use Arc\Transformers\Channels\ChannelUserTransformer;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $broadcast = Container::getInstance()->make(BroadcastManager::class);
        $broadcast->routes([
            'middleware' => 'user',
        ]);

        /**
         * @var \Illuminate\Broadcasting\Broadcasters\PusherBroadcaster $connection
         */
        $connection = $broadcast->connection('pusher');
        $connection->channel('users', static function (?User $user) {
            return $user !== null;
        })->channel('channel.{channelUuid}', static function (User $user, $channelUuid) {
            $channel = $user->channels()->where('channels.uuid', '=', $channelUuid)->withPivot(['permissions'])->first();

            if ($channel) {
                $user->channel_permissions = $channel->pivot->permissions;
                return (new ChannelUserTransformer)->transform($user);
            }
        });
    }
}
