<?php

declare(strict_types=1);

use BuiltFast\PHPStan\Rules\SensitiveParameterDetectorRule;

it('implements PHPStan Rule interface', function () {
    expect(new SensitiveParameterDetectorRule())->toBeInstanceOf(PHPStan\Rules\Rule::class);
});

it('returns FunctionLike as node type', function () {
    expect((new SensitiveParameterDetectorRule())->getNodeType())->toBe(PhpParser\Node\FunctionLike::class);
});

it('validates sensitive keywords from the rule', function () {
    $rule = new SensitiveParameterDetectorRule();
    $reflection = new ReflectionClass($rule);
    $property = $reflection->getProperty('sensitiveKeywords');
    $property->setAccessible(true);
    $sensitiveKeywords = $property->getValue($rule);

    expect($sensitiveKeywords)->toContain('password');
    expect($sensitiveKeywords)->toContain('secret');
    expect($sensitiveKeywords)->toContain('token');
    expect($sensitiveKeywords)->toContain('key');

    expect(count($sensitiveKeywords))->toBeGreaterThan(5);
});
