<?php

declare(strict_types=1);

namespace Tests;

use BuiltFast\Rules\SensitiveParameterDetectorRule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<SensitiveParameterDetectorRule>
 */
abstract class TestCase extends RuleTestCase
{
    protected SensitiveParameterDetectorRule $rule;

    protected function getRule(): SensitiveParameterDetectorRule
    {
        return $this->rule ?? new SensitiveParameterDetectorRule();
    }
}
