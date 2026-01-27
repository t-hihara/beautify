<?php

namespace App\Enum\Traits;

trait HasOptions
{
    abstract public function description(): string;

    public function options(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'id'   => $case->value,
            'name' => $case->description(),
        ]);
    }
}
