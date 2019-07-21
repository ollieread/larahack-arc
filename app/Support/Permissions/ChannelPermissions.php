<?php

namespace Arc\Support\Permissions;

class ChannelPermissions extends BasePermissions
{
    public const DEFAULT_PERMISSIONS = self::VIEW_CHANNEL | self::SEND_MESSAGES | self::READ_MESSAGE_HISTORY;

    public const VIEW_CHANNEL         = 0x00000001;
    public const SEND_MESSAGES        = 0x00000002;
    public const READ_MESSAGE_HISTORY = 0x00000004;
    public const KICK_MEMBERS         = 0x00000008;
    public const BAN_MEMBERS          = 0x00000010;
    public const DELETE_MESSAGES      = 0x00000020;
    public const ADMINISTER           = 0x00000040;

    public static function allPermissions(): array
    {
        return [
            'VIEW_CHANNEL'         => self::VIEW_CHANNEL,
            'SEND_MESSAGES'        => self::SEND_MESSAGES,
            'READ_MESSAGE_HISTORY' => self::READ_MESSAGE_HISTORY,
            'KICK_MEMBERS'         => self::KICK_MEMBERS,
            'BAN_MEMBERS'          => self::BAN_MEMBERS,
            'DELETE_MESSAGES'      => self::DELETE_MESSAGES,
            'ADMINISTER'           => self::ADMINISTER,
        ];
    }
}