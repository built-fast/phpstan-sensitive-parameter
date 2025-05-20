<?php

declare(strict_types=1);

namespace BuiltFast\PHPStan;

use BuiltFast\PHPStan\Rules\SensitiveParameterDetectorRule;

final class SensitiveParameterDetectorRuleFactory
{
    /** @var string[] */
    private array $additionalKeywords;

    /**
     * @param  string[]  $additionalKeywords
     */
    public function __construct(array $additionalKeywords = [])
    {
        $this->additionalKeywords = $additionalKeywords;
    }

    public function create(): SensitiveParameterDetectorRule
    {
        return new SensitiveParameterDetectorRule($this->additionalKeywords);
    }
}
