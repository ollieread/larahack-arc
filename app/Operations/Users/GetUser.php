<?php

namespace Arc\Operations\Users;

use Arc\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;

class GetUser
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $username;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \Ramsey\Uuid\Uuid
     */
    private $uuid;

    public function perform(): ?User
    {
        $query = User::query();

        if ($this->username && $this->email) {
            $query->where(function (Builder $query) {
                $query->where('email', '=', $this->email)
                      ->orWhere('username', '=', $this->username);
            });
        } else {
            if ($this->username) {
                $query->where('username', '=', $this->username);
            }

            if ($this->email) {
                $query->where('email', '=', $this->email);
            }
        }

        if ($this->id) {
            $query->where('id', '=', $this->id);
        }

        if ($this->uuid) {
            $query->where('uuid', '=', $this->uuid->toString());
        }

        return $query->first();
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
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