<?php

declare(strict_types=1);

final class IgnoreComments
{
    // This should trigger a warning
    public function normalFunction(string $password): void
    {
        // Implementation
    }

    // @phpstan-ignore-next-line sensitiveParameter.missing
    public function ignoredFunction(string $password): void
    {
        // Implementation with ignore comment
    }

    public function anotherFunction(string $secret): void
    {
        // This should also trigger a warning
    }
}
