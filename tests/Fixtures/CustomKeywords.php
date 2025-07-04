<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use SensitiveParameter;

/**
 * Test fixture for testing custom sensitive keywords
 * This will be used with custom configuration to test additional keywords
 */
final class CustomKeywords
{
    // These should trigger when 'banking' and 'medical' are added as custom keywords
    public function bankingMethod(string $banking, string $bankingInfo): void
    {
        // Should trigger with custom keywords
    }

    public function medicalMethod(string $medical, string $medicalRecord): void
    {
        // Should trigger with custom keywords
    }

    // Mix of default and custom keywords
    public function mixedKeywords(string $password, string $banking, string $regularParam): void
    {
        // $password should always trigger, $banking only with custom config
    }

    // Custom keywords with compounds
    public function customCompounds(string $userBanking, string $patientMedical): void
    {
        // Should trigger with custom keywords
    }

    // Case variations of custom keywords
    public function customCaseVariations(string $Banking, string $MEDICAL): void
    {
        // Should trigger with custom keywords
    }

    // Parameters that would not trigger without custom keywords
    public function withoutCustomKeywords(string $account, string $patient, string $record): void
    {
        // Should NOT trigger unless these are added as custom keywords
    }

    // Testing that custom keywords work with function-level protection
    #[SensitiveParameter]
    public function protectedCustomKeywords(string $banking, string $medical): void
    {
        // Should NOT trigger even with custom keywords due to function-level protection
    }

    // Testing parameter-level protection with custom keywords
    public function parameterProtectedCustom(#[SensitiveParameter] string $banking, string $medical): void
    {
        // Only $medical should trigger with custom keywords
    }
}
