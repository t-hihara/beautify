<?php

namespace App\Utilities;

use Illuminate\Support\Str;

class RecursiveCovert
{
    public static function _convert(array|object $items, string $method): array|object
    {
        if (is_array($items)) {
            return self::convertArray($items, $method);
        }

        return self::convertObject($items, $method);
    }

    private static function convertArray(array $items, string $method): array
    {
        $newItems = [];

        foreach ($items as $key => $value) {
            $newKey = Str::$method($key);

            if (is_array($value)) {
                $newItems[$newKey] = self::convertArray($value, $method);
            } else if (is_object($value)) {
                $newItems[$newKey] = self::convertObject($value, $method);
            } else {
                $newItems[$newKey] = $value;
            }
        }

        return $newItems;
    }

    private static function convertObject(object $items, string $method): object
    {
        $newObject = clone $items;

        foreach ($items as $key => $value) {
            $newKey = Str::$method[$key];

            if (is_array($value)) {
                $newObject->{$newKey} = self::convertArray($value, $method);
            } else if (is_object($value)) {
                $newObject->{$newKey} = self::convertObject($value, $method);
            } else {
                $newObject->{$newKey} = $value;
            }
        }

        return $newObject;
    }
}
