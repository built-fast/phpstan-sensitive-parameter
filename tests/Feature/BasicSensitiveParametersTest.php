<?php

declare(strict_types=1);

use BuiltFast\Rules\SensitiveParameterDetectorRule;

beforeEach(function () {
    $this->rule = new SensitiveParameterDetectorRule();
});

it('detects basic sensitive parameters', function () {
    $this->analyse([__DIR__.'/../Fixtures/BasicSensitiveParameters.php'], [
        [
            'Parameter $userSecret in Tests\\Fixtures\\BasicSensitiveParameters::regularFunction might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            13,
        ],
        [
            'Parameter $credential in Tests\\Fixtures\\BasicSensitiveParameters::regularFunction might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            13,
        ],
        [
            'Parameter $password in Tests\\Fixtures\\BasicSensitiveParameters::authenticate might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            19,
        ],
        [
            'Parameter $apikey in Tests\\Fixtures\\BasicSensitiveParameters::setApiCredentials might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            24,
        ],
        [
            'Parameter $apisecret in Tests\\Fixtures\\BasicSensitiveParameters::setApiCredentials might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            24,
        ],
        [
            'Parameter $cardNumber in Tests\\Fixtures\\BasicSensitiveParameters::processPayment might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            29,
        ],
        [
            'Parameter $cvv in Tests\\Fixtures\\BasicSensitiveParameters::processPayment might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            29,
        ],
        [
            'Parameter $creditCardToken in Tests\\Fixtures\\BasicSensitiveParameters::processPayment might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            29,
        ],
        [
            'Parameter $ssn in Tests\\Fixtures\\BasicSensitiveParameters::storeUserData might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            34,
        ],
        [
            'Parameter $privateKey in Tests\\Fixtures\\BasicSensitiveParameters::storeUserData might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            34,
        ],
        [
            'Parameter $authToken in Tests\\Fixtures\\BasicSensitiveParameters::handleTokens might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            39,
        ],
        [
            'Parameter $refreshToken in Tests\\Fixtures\\BasicSensitiveParameters::handleTokens might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            39,
        ],
        [
            'Parameter $accessToken in Tests\\Fixtures\\BasicSensitiveParameters::handleTokens might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            39,
        ],
        [
            'Parameter $password in globalAuthFunction might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            46,
        ],
    ]);
});

test('does not warn for protected sensitive parameters', function () {
    $this->analyse([__DIR__.'/../Fixtures/ProtectedSensitiveParameters.php'], [
        [
            'Parameter $apikey in Tests\\Fixtures\\ProtectedSensitiveParameters::setApiCredentials might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            22,
        ],
        [
            'Parameter $cvv in Tests\\Fixtures\\ProtectedSensitiveParameters::processPayment might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            28,
        ],
    ]);
});

test('detects edge cases correctly', function () {
    $this->analyse([__DIR__.'/../Fixtures/EdgeCases.php'], [
        [
            'Parameter $apikey in Tests\\Fixtures\\EdgeCases::__construct might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            13,
        ],
        [
            'Parameter $credential in Tests\\Fixtures\\EdgeCases::staticMethod might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            18,
        ],
        [
            'Parameter $userPassword in Tests\\Fixtures\\EdgeCases::partialMatches might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            24,
        ],
        [
            'Parameter $secretKey in Tests\\Fixtures\\EdgeCases::partialMatches might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            24,
        ],
        [
            'Parameter $apiToken in Tests\\Fixtures\\EdgeCases::partialMatches might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            24,
        ],
        [
            'Parameter $Password in Tests\\Fixtures\\EdgeCases::caseVariations might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            30,
        ],
        [
            'Parameter $SECRET in Tests\\Fixtures\\EdgeCases::caseVariations might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            30,
        ],
        [
            'Parameter $Token in Tests\\Fixtures\\EdgeCases::caseVariations might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            30,
        ],
        [
            'Parameter $myPassword in Tests\\Fixtures\\EdgeCases::mixedCaseCompounds might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            36,
        ],
        [
            'Parameter $userSecret in Tests\\Fixtures\\EdgeCases::mixedCaseCompounds might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            36,
        ],
        [
            'Parameter $appToken in Tests\\Fixtures\\EdgeCases::mixedCaseCompounds might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            36,
        ],
        [
            'Parameter $passwordless in Tests\\Fixtures\\EdgeCases::falsePositives might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            42,
        ],
        [
            'Parameter $secretion in Tests\\Fixtures\\EdgeCases::falsePositives might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            42,
        ],
        [
            'Parameter $password in Tests\\Fixtures\\EdgeCases::multiplePasswords might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            49,
        ],
        [
            'Parameter $confirmPassword in Tests\\Fixtures\\EdgeCases::multiplePasswords might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            49,
        ],
        [
            'Parameter $oldPassword in Tests\\Fixtures\\EdgeCases::multiplePasswords might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            49,
        ],
        [
            'Parameter $password123 in Tests\\Fixtures\\EdgeCases::unusualCases might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            55,
        ],
        [
            'Parameter $password_hash in Tests\\Fixtures\\EdgeCases::unusualCases might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            55,
        ],
        [
            'Parameter $secret in Tests\\Fixtures\\EdgeCases::privateMethod might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            61,
        ],
        [
            'Parameter $token in Tests\\Fixtures\\EdgeCases::protectedMethod might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            66,
        ],
    ]);
});
