<?php

namespace App\Models\Concerns;

use Hashids\Hashids;

trait UsaHashid
{
    protected static function hashids(): Hashids
    {
        return new Hashids(config('app.key') . static::class, 10);
    }

    public function getRouteKey()
    {
        return static::hashids()->encode($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $id = static::hashids()->decode($value)[0] ?? null;

        return $id ? $this->where($this->getKeyName(), $id)->first() : null;
    }
}