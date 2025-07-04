<?php

declare(strict_types=1);

namespace Tests\Fixtures;

/**
 * Test fixture with basic sensitive parameters that should trigger warnings
 */
final class BasicSensitiveParameters
{
    // Regular function (not class method)
    public static function regularFunction(string $userSecret, string $credential): string
    {
        return '';
    }

    // Should trigger warnings - no SensitiveParameter attribute
    public function authenticate(string $username, string $password): bool
    {
        return true;
    }

    public function setApiCredentials(string $apikey, string $apisecret): void
    {
        // Implementation
    }

    public function processPayment(string $cardNumber, string $cvv, string $creditCardToken): bool
    {
        return true;
    }

    public function storeUserData(string $name, string $ssn, string $privateKey): void
    {
        // Implementation
    }

    public function handleTokens(string $authToken, string $refreshToken, string $accessToken): array
    {
        return [];
    }
}

// Global function should also be detected
function globalAuthFunction(string $password, string $email): bool
{
    return true;
}
