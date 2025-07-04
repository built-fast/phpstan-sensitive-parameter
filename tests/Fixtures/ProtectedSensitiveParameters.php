<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use SensitiveParameter;

/**
 * Test fixture with properly protected sensitive parameters that should NOT trigger warnings
 */
final class ProtectedSensitiveParameters
{
    // Function-level SensitiveParameter - should NOT trigger warnings
    #[SensitiveParameter]
    public function authenticate(string $username, string $password): bool
    {
        return true;
    }

    // Parameter-level SensitiveParameter - should NOT trigger warnings
    public function setApiCredentials(string $apikey, #[SensitiveParameter] string $apisecret): void
    {
        // Implementation
    }

    // Mixed - some protected, some not
    public function processPayment(#[SensitiveParameter] string $cardNumber, string $amount, string $cvv): bool
    {
        return true; // $cvv should still trigger warning
    }

    // All parameters protected with parameter-level attributes
    public function storeCredentials(
        #[SensitiveParameter] string $password,
        #[SensitiveParameter] string $secret,
        #[SensitiveParameter] string $token
    ): void {
        // Implementation
    }

    // Non-sensitive parameters should never trigger
    public function regularMethod(string $name, string $email, int $age): void
    {
        // Implementation
    }
}

// Global function with SensitiveParameter attribute
#[SensitiveParameter]
function protectedGlobalFunction(string $password, string $secret): bool
{
    return true;
}
