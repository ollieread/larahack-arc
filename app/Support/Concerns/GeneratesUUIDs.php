<?php

namespace Arc\Support\Concerns;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait GeneratesUUIDs
{
    public static function bootGeneratesUUIDs(): void
    {
        static::creating(static function (Model $model) {
            $model->uuid = Uuid::uuid4();
        });
    }
}