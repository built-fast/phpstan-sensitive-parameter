<?php

declare(strict_types=1);

use BuiltFast\Rules\SensitiveParameterDetectorRule;

beforeEach(function () {
    $this->rule = new SensitiveParameterDetectorRule(['banking', 'medical']);
});

it('detects custom keywords correctly', function () {
    $this->analyse([__DIR__.'/../Fixtures/CustomKeywords.php'], [
        [
            'Parameter $banking in Tests\\Fixtures\\CustomKeywords::bankingMethod might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            16,
        ],
        [
            'Parameter $bankingInfo in Tests\\Fixtures\\CustomKeywords::bankingMethod might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            16,
        ],
        [
            'Parameter $medical in Tests\\Fixtures\\CustomKeywords::medicalMethod might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            21,
        ],
        [
            'Parameter $medicalRecord in Tests\\Fixtures\\CustomKeywords::medicalMethod might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            21,
        ],
        [
            'Parameter $banking in Tests\\Fixtures\\CustomKeywords::mixedKeywords might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            27,
        ],
        [
            'Parameter $userBanking in Tests\\Fixtures\\CustomKeywords::customCompounds might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            33,
        ],
        [
            'Parameter $patientMedical in Tests\\Fixtures\\CustomKeywords::customCompounds might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            33,
        ],
        [
            'Parameter $Banking in Tests\\Fixtures\\CustomKeywords::customCaseVariations might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            39,
        ],
        [
            'Parameter $MEDICAL in Tests\\Fixtures\\CustomKeywords::customCaseVariations might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            39,
        ],
        [
            'Parameter $medical in Tests\\Fixtures\\CustomKeywords::parameterProtectedCustom might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            58,
        ],
    ]);
});
