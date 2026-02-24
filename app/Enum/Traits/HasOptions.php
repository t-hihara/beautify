<?php

namespace App\Enum\Traits;

trait HasOptions
{
    abstract public function description(): string;

    public static function options(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'id'   => $case->value,
            'name' => $case->description(),
        ])->toArray();
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
