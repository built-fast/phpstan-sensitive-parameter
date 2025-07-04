<?php

declare(strict_types=1);

use BuiltFast\Rules\SensitiveParameterDetectorRule;
use PhpParser\Node\FunctionLike;
use PHPStan\Rules\Rule;

it('implements PHPStan Rule interface', function () {
    expect(new SensitiveParameterDetectorRule())->toBeInstanceOf(Rule::class);
});

it('returns FunctionLike as node type', function () {
    expect((new SensitiveParameterDetectorRule())->getNodeType())->toBe(FunctionLike::class);
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
    expect($sensitiveKeywords)->toContain('apikey');

    expect(count($sensitiveKeywords))->toBeGreaterThan(5);
});

it('uses default keywords when no custom keywords provided', function () {
    $rule = new SensitiveParameterDetectorRule([]);
    $reflection = new ReflectionClass($rule);
    $property = $reflection->getProperty('sensitiveKeywords');
    $property->setAccessible(true);
    $sensitiveKeywords = $property->getValue($rule);

    expect($sensitiveKeywords)->toContain('password');
    expect($sensitiveKeywords)->toContain('secret');
    expect($sensitiveKeywords)->toContain('token');
});

it('overrides default keywords with custom keywords', function () {
    $customKeywords = ['custom1', 'custom2'];
    $rule = new SensitiveParameterDetectorRule($customKeywords);
    $reflection = new ReflectionClass($rule);
    $property = $reflection->getProperty('sensitiveKeywords');
    $property->setAccessible(true);
    $sensitiveKeywords = $property->getValue($rule);

    expect($sensitiveKeywords)->toBe($customKeywords);
    expect($sensitiveKeywords)->not->toContain('password');
    expect($sensitiveKeywords)->toContain('custom1');
    expect($sensitiveKeywords)->toContain('custom2');
});

it('accepts empty custom keywords array', function () {
    $rule = new SensitiveParameterDetectorRule([]);

    expect($rule)->toBeInstanceOf(SensitiveParameterDetectorRule::class);
});

it('maintains case sensitivity for custom keywords', function () {
    $customKeywords = ['Password', 'SECRET', 'Token'];
    $rule = new SensitiveParameterDetectorRule($customKeywords);
    $reflection = new ReflectionClass($rule);
    $property = $reflection->getProperty('sensitiveKeywords');
    $property->setAccessible(true);
    $sensitiveKeywords = $property->getValue($rule);

    expect($sensitiveKeywords)->toContain('Password');
    expect($sensitiveKeywords)->toContain('SECRET');
    expect($sensitiveKeywords)->toContain('Token');
});

it('handles duplicate keywords in custom array', function () {
    $customKeywords = ['password', 'password', 'secret'];
    $rule = new SensitiveParameterDetectorRule($customKeywords);
    $reflection = new ReflectionClass($rule);
    $property = $reflection->getProperty('sensitiveKeywords');
    $property->setAccessible(true);
    $sensitiveKeywords = $property->getValue($rule);

    expect($sensitiveKeywords)->toBe($customKeywords); // Should preserve original array
});

it('has comprehensive default keyword coverage', function () {
    $rule = new SensitiveParameterDetectorRule();
    $reflection = new ReflectionClass($rule);
    $property = $reflection->getProperty('sensitiveKeywords');
    $property->setAccessible(true);
    $keywords = $property->getValue($rule);

    // Authentication keywords
    expect($keywords)->toContain('password');
    expect($keywords)->toContain('secret');
    expect($keywords)->toContain('token');
    expect($keywords)->toContain('credential');
    expect($keywords)->toContain('auth');
    expect($keywords)->toContain('bearer');

    // API Security keywords
    expect($keywords)->toContain('apikey');

    // Financial keywords
    expect($keywords)->toContain('credit');
    expect($keywords)->toContain('card');
    expect($keywords)->toContain('cvv');
    expect($keywords)->toContain('ssn');
    expect($keywords)->toContain('pin');

    // Security keywords
    expect($keywords)->toContain('private');
    expect($keywords)->toContain('signature');
    expect($keywords)->toContain('hash');
    expect($keywords)->toContain('salt');
    expect($keywords)->toContain('nonce');
    expect($keywords)->toContain('otp');
    expect($keywords)->toContain('passcode');
    expect($keywords)->toContain('csrf');
});
