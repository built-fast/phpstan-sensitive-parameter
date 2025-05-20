<?php

declare(strict_types=1);

namespace BuiltFast\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects potential parameters that might need SensitiveParameter attribute
 */
final class SensitiveParameterDetectorRule implements Rule
{
    /** @var string[] */
    private array $sensitiveKeywords;

    /**
     * @param  string[]  $sensitiveKeywords
     */
    public function __construct(array $sensitiveKeywords = [])
    {
        // Merge custom keywords with default keywords
        $this->sensitiveKeywords = array_merge(
            [
                'password', 'secret', 'token', 'key', 'credential', 'auth',
                'credit', 'card', 'ccv', 'cvv', 'ssn', 'api', 'private',
            ],
            $sensitiveKeywords
        );
    }

    public function getNodeType(): string
    {
        return FunctionLike::class;
    }

    /**
     * @return RuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        if (! $node instanceof ClassMethod && ! $node instanceof Function_) {
            return $errors;
        }

        $hasSensitiveAttribute = false;

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attrName = $attr->name->toString();
                if (
                    $attrName === 'SensitiveParameter' ||
                    $attrName === '\SensitiveParameter' ||
                    mb_strpos($attrName, 'SensitiveParameter') !== false
                ) {
                    $hasSensitiveAttribute = true;
                    break 2;
                }
            }
        }

        foreach ($node->getParams() as $param) {
            if (! $param->var instanceof Node\Expr\Variable || ! is_string($param->var->name)) {
                continue;
            }

            $paramName = $param->var->name;

            $paramHasSensitiveAttribute = false;

            if ($param->attrGroups) {
                foreach ($param->attrGroups as $attrGroup) {
                    foreach ($attrGroup->attrs as $attr) {
                        $attrName = $attr->name->toString();
                        if (
                            $attrName === 'SensitiveParameter' ||
                            $attrName === '\SensitiveParameter' ||
                            mb_strpos($attrName, 'SensitiveParameter') !== false
                        ) {
                            $paramHasSensitiveAttribute = true;
                            break 2;
                        }
                    }
                }
            }

            if ($hasSensitiveAttribute || $paramHasSensitiveAttribute) {
                continue;
            }

            foreach ($this->sensitiveKeywords as $keyword) {
                if (mb_stripos($paramName, $keyword) !== false) {
                    $functionName = $node instanceof ClassMethod
                        ? ($scope->getClassReflection() ? $scope->getClassReflection()->getName().'::' : '').$node->name->name
                        : $node->name->name;

                    $errors[] = RuleErrorBuilder::message(sprintf(
                        'Parameter $%s in %s might contain sensitive information. Consider using #[\\SensitiveParameter] attribute.',
                        $paramName,
                        $functionName
                    ))
                        ->build();
                    break;
                }
            }
        }

        return $errors;
    }
}
