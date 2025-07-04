<?php

declare(strict_types=1);

namespace Tests\Fixtures;

/**
 * Test fixture with edge cases for sensitive parameter detection
 */
final class EdgeCases
{
    // Constructor
    public function __construct(string $apikey, string $normalParam)
    {
        // $apikey should trigger
    }

    public static function staticMethod(string $credential): void
    {
        // Should trigger
    }

    // Partial matches should trigger
    public function partialMatches(string $userPassword, string $secretKey, string $apiToken): void
    {
        // All should trigger warnings
    }

    // Case variations should trigger
    public function caseVariations(string $Password, string $SECRET, string $Token): void
    {
        // All should trigger warnings
    }

    // Mixed case compounds should trigger
    public function mixedCaseCompounds(string $myPassword, string $userSecret, string $appToken): void
    {
        // All should trigger warnings
    }

    // Words that contain sensitive keywords but aren't actually sensitive
    public function falsePositives(string $passenger, string $passwordless, string $secretion): void
    {
        // These should still trigger because they contain the keywords
        // The rule is intentionally broad to catch potential issues
    }

    // Multiple parameters with same keyword
    public function multiplePasswords(string $password, string $confirmPassword, string $oldPassword): void
    {
        // All should trigger warnings
    }

    // Unusual parameter names
    public function unusualCases(string $password123, string $password_hash): void
    {
        // $password123 and $password_hash should trigger
    }

    // Different visibility levels
    private function privateMethod(string $secret): void
    {
        // Should trigger
    }

    private function protectedMethod(string $token): void
    {
        // Should trigger
    }
}
