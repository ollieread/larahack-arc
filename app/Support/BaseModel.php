<?php

namespace Arc\Support;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class BaseModel extends Model
{
    protected function castAttribute($key, $value)
    {
        if ($value === null) {
            return $value;
        }

        if ($this->getCastType($key) === 'uuid') {
            return Uuid::fromString($value);
        }

        return parent::castAttribute($key, $value);
    }

    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = $this->newInstance($attributes, true);

        $model->setRawAttributes((array)$attributes, true);

        $model->setConnection($connection ?: $this->getConnectionName());

        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    public function newInstance($attributes = [], $exists = false)
    {
        $model = new static((array)$attributes);

        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        $model->setTable($this->getTable());

        return $model;
    }

}