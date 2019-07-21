<?php

namespace Arc\Operations\Channels;

use Arc\Models\Channel;
use Arc\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;

class GetChannels
{
    /**
     * @var \Arc\Models\User|null
     */
    private $user;

    /**
     * @var bool
     */
    private $userOnly = false;

    /**
     * @var bool
     */
    private $includePrivate = false;

    /**
     * @var bool
     */
    private $includeArchived = false;

    /**
     * @var bool
     */
    private $default = false;

    /**
     * @var bool
     */
    private $withMessages = false;

    public function perform()
    {
        $query = Channel::query();

        if ($this->userOnly) {
            $query->whereHas('users', function (Builder $query) {
                $query->where('users.id', '=', $this->user->id);
            });
        }

        if ($this->default) {
            $query->where('default', '=', 1);
        }

        if ($this->withMessages) {
            $query->with(['messages' => static function (HasMany $query) {
                $query->select(['messages.*'])
                      ->selectRaw('users.uuid as user_uuid')
                      ->leftJoin('users', static function (JoinClause $join) {
                          $join->on('users.id', '=', 'messages.user_id');
                      })
                      ->orderBy('created_at', 'desc')
                      ->limit(100);
            }]);
        }

        if (! $this->userOnly && $this->user) {
            $query->where(function (Builder $query) {
                $query->whereHas('users', function (Builder $query) {
                    $query->where('users.id', '=', $this->user->id);
                })->where('private', '=', 1);
            });

            if (! $this->includePrivate) {
                $query->orWhere('private', '=', 0);
            }
        } else if (! $this->includePrivate) {
            $query->where('private', '=', 0);
        }

        if (! $this->includeArchived) {
            $query->where('active', '=', 1);
        }

        return $query->get();
    }

    /**
     * @param bool $default
     *
     * @return $this
     */
    public function setDefault(bool $default): self
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @param bool $includeArchived
     *
     * @return $this
     */
    public function setIncludeArchived(bool $includeArchived): self
    {
        $this->includeArchived = $includeArchived;
        return $this;
    }

    /**
     * @param bool $includePrivate
     *
     * @return $this
     */
    public function setIncludePrivate(bool $includePrivate): self
    {
        $this->includePrivate = $includePrivate;
        return $this;
    }

    /**
     * @param \Arc\Models\User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param bool $userOnly
     *
     * @return $this
     */
    public function setUserOnly(bool $userOnly): self
    {
        $this->userOnly = $userOnly;
        return $this;
    }

    /**
     * @param bool $withMessages
     *
     * @return $this
     */
    public function setWithMessages(bool $withMessages): self
    {
        $this->withMessages = $withMessages;
        return $this;
    }
}