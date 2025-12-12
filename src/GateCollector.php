<?php

namespace Niladam\QuickLinks;

use Illuminate\Support\Facades\Gate;

class GateCollector
{
    protected static bool $listening = false;

    /**
     * @var array<int, array{ability: string, arguments: array}>
     */
    protected static array $entries = [];

    public static function register(): void
    {
        if (static::$listening || ! static::enabled()) {
            return;
        }

        Gate::after(function ($user, string $ability, mixed $result, array $arguments = []): void {
            static::record($ability, $arguments);
        });

        static::$listening = true;
    }

    public static function record(string $ability, array $arguments): void
    {
        if (! static::enabled()) {
            return;
        }

        static::$entries[] = [
            'ability' => $ability,
            'arguments' => $arguments,
        ];
    }

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        if (! static::enabled()) {
            return [];
        }

        return collect(static::$entries)
            ->map(fn (array $entry): string => static::formatEntry($entry))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    protected static function formatEntry(array $entry): string
    {
        $subject = static::extractSubject($entry['arguments']);

        if ($subject === null) {
            return $entry['ability'];
        }

        return sprintf('%s:%s', $entry['ability'], $subject);
    }

    protected static function extractSubject(array $arguments): ?string
    {
        foreach ($arguments as $argument) {
            if (is_object($argument)) {
                return class_basename($argument);
            }

            if (is_string($argument)) {
                return class_basename($argument);
            }
        }

        return null;
    }

    protected static function enabled(): bool
    {
        return config('quick-links.permissions', true);
    }
}
