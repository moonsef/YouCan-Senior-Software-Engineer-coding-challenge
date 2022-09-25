<?php

namespace YouCan\Entities;




use Countable;
use Iterator;

class LocationCollection implements \ArrayAccess, Iterator, Countable
{
    private static array $container;
    private static array $keys;
    private static int $position;

    public static function createFromArray(array $attributes): self
    {
        self::$position = 0;
        foreach ($attributes as $attribute) {
            self::$container[] = new Location(
                $attribute['formatted_address'],
                $attribute['geometry']['location']['lat'],
                $attribute['geometry']['location']['lng'],
                $attribute['place_id'],
            );
        }

        self::$keys = array_keys(self::$container);
        return new self();
    }

    public function offsetExists($offset): bool
    {
        return isset(self::$container[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset): ?Location
    {
        return self::$container[$offset] ?? null;
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            self::$container[] = $value;
        } else {
            self::$container[$offset] = $value;
        }
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset(self::$container[$offset]);
    }


    #[\ReturnTypeWillChange]
    public function rewind() {
        self::$position = 0;
    }

    #[\ReturnTypeWillChange]
    public function current() {
        return self::$container[self::$keys[self::$position]];
    }

    #[\ReturnTypeWillChange]
    public function key() {
        return self::$keys[self::$position];
    }

    #[\ReturnTypeWillChange]
    public function next() {
        ++self::$position;
    }

    #[\ReturnTypeWillChange]
    public function valid(): bool
    {
        return isset(self::$keys[self::$position]);
    }

    #[\ReturnTypeWillChange]
    public function count(): int
    {
        return count(self::$container);
    }
}
