<?php

namespace Arc\Models;

use Arc\Support\Concerns\GeneratesUUIDs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use GeneratesUUIDs;

    public const TEXT    = 0x00000001;
    public const MEDIA   = 0x00000002;
    public const SPOILER = 0x00000004;
    public const NSFW    = 0x00000008;
    public const SYSTEM  = 0x00000010;
    public const ACTION  = 0x00000020;
    public const NOTICE  = 0x00000040;

    public const TYPES = [
        self::TEXT,
        self::MEDIA,
        self::SPOILER,
        self::NSFW,
        self::SYSTEM,
        self::ACTION,
        self::NOTICE,
    ];

    protected $table = 'messages';

    protected $fillable = [
        'type',
        'message',
        'action',
        'media',
        'mentions',
        'metadata',
    ];

    protected $casts = [
        'type'     => 'int',
        'mentions' => 'json',
        'metadata' => 'json',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}