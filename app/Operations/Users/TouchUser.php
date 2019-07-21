<?php

namespace Arc\Operations\Users;

use Arc\Models\User;

class TouchUser
{
    /**
     * @var \Arc\Models\User
     */
    private $user;

    public function perform(): void
    {
        $this->user->touch();
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