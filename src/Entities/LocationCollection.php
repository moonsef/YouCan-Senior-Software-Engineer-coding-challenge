<?php

namespace YouCan\Entities;


class LocationCollection implements \ArrayAccess
{
    private static array $container;

    public static function createFromArray(array $attributes): self
    {
        foreach ($attributes as $attribute) {
            self::$container[] = new Location(
                $attribute['formatted_address'],
                $attribute['geometry']['location']['lat'],
                $attribute['geometry']['location']['lng'],
                $attribute['place_id'],
            );
        }

        return new self();
    }

    public function offsetExists($offset): bool
    {
        return isset(self::$container[$offset]);
    }

    public function offsetGet($offset): ?Location
    {
        return self::$container[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            self::$container[] = $value;
        } else {
            self::$container[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset(self::$container[$offset]);
    }
}
