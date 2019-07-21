<?php

namespace Arc\Models;

use Arc\Support\Concerns\GeneratesUUIDs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int               $id
 * @property \Ramsey\Uuid\Uuid uuid
 * @property string            $name
 * @property string            $description
 * @property bool              $private
 * @property bool              $active
 * @property bool              $default
 * @property \Carbon\Carbon    $created_at
 * @property \Carbon\Carbon    $updated_at
 */
class Channel extends Model
{
    use GeneratesUUIDs;

    protected $table = 'channels';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'private',
        'active',
        'default',
    ];

    protected $casts = [
        'private' => 'bool',
        'active'  => 'bool',
        'default' => 'bool',
        'uuid'    => 'uuid',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'channel_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_channels', 'channel_id', 'user_id')
                    ->withPivot(['permissions'])
                    ->withTimestamps();
    }
}