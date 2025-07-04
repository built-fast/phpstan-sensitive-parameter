<?php

declare(strict_types=1);

it('detects normal parameters and respects ignore comments', function () {
    $this->analyse([__DIR__.'/../Fixtures/IgnoreComments.php'], [
        [
            'Parameter $password in IgnoreComments::normalFunction might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            8,
        ],
        [
            'Parameter $secret in IgnoreComments::anotherFunction might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
            19,
        ],
    ]);
});
