<?php

namespace Arc\Models;

use Arc\Support\BaseModel;
use Arc\Support\Concerns\GeneratesUUIDs;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * @property int               $id
 * @property \Ramsey\Uuid\Uuid uuid
 * @property string            $username
 * @property string            $email
 * @property string            $password
 * @property string            $remember_token
 * @property \Carbon\Carbon    $created_at
 * @property \Carbon\Carbon    $updated_at
 */
class User extends BaseModel implements AuthorizableContract, CanResetPasswordContract
{
    use GeneratesUUIDs, Authorizable, CanResetPassword, MustVerifyEmail;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'remember_token',
    ];

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'user_channels', 'user_id', 'channel_id')
                    ->withPivot(['permissions'])
                    ->withTimestamps();
    }

    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getAuthIdentifier()
    {
        return $this->uuid;
    }
}