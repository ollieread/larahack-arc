<?php

namespace Arc\Support\Permissions;

abstract class BasePermissions
{
    abstract public static function allPermissions(): array;

    /**
     * Parse a permission int to an array of useable permissions.
     *
     * @param int $permissions
     *
     * @return array
     */
    public static function toArray(int $permissions): array
    {
        $parsedPermissions = [];
        foreach (self::allPermissions() as $permission => $value) {
            $parsedPermissions[$permission] = (bool)($permissions & $value);
        }
        return $parsedPermissions;
    }
    /**
     * Parse a permission array to an int for sending over the API.
     *
     * @param array $permissions
     *
     * @return int
     */
    public static function toInt(array $permissions): int
    {
        $parsedPermissions = 0;
        foreach ($permissions as $permission => $presence) {
            $permission = strtoupper($permission);
            if ($presence && array_key_exists($permission, self::allPermissions())) {
                $parsedPermissions |= self::allPermissions()[$permission];
            }
        }
        return $parsedPermissions;
    }
    /**
     * Check whether a given permission exists.
     *
     * @param string $permission
     *
     * @return bool
     */
    public static function exists(string $permission): bool
    {
        return array_key_exists(strtoupper($permission), self::allPermissions());
    }
}