<?php

declare(strict_types=1);

namespace BuiltFast\Rules;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * PHPStan rule that detects parameters containing sensitive information that
 * should be marked with the #[\SensitiveParameter] attribute to prevent
 * exposure in stack traces.
 *
 * This rule performs case-insensitive substring matching against a
 * configurable list of sensitive keywords. When a parameter name contains any
 * of these keywords and is not already protected by the #[\SensitiveParameter]
 * attribute (either at the function level or parameter level), it will trigger
 * a warning.
 *
 * The rule supports custom keyword lists that completely override the default
 * keywords, allowing teams to tailor the detection to their specific security
 * requirements.
 *
 * @implements Rule<FunctionLike>
 */
final class SensitiveParameterDetectorRule implements Rule
{
    /** @var string[] Array of sensitive keywords to match against */
    private array $sensitiveKeywords;

    /**
     * Initialize the rule with custom or default sensitive keywords.
     *
     * When custom keywords are provided, they completely replace the default
     * keywords. When an empty array is provided, the default keywords are
     * used.
     *
     * Default keywords include common sensitive terms like 'password',
     * 'secret', 'token', 'apikey', 'credit', 'ssn', 'hash', and many others
     * covering authentication, financial, and security-related parameters.
     *
     * Custom keywords should be lowercase strings that will be matched
     * case-insensitively against parameter names using substring matching
     * (e.g., 'secret' matches 'userSecret').
     *
     * @param string[] $sensitiveKeywords Array of custom keywords to use
     * instead of defaults
     */
    public function __construct(array $sensitiveKeywords = [])
    {
        // Use provided keywords or fall back to defaults
        $this->sensitiveKeywords = empty($sensitiveKeywords) ? [
            'apikey',
            'auth',
            'bearer',
            'card',
            'ccv',
            'credential',
            'credit',
            'csrf',
            'cvv',
            'hash',
            'nonce',
            'otp',
            'passcode',
            'password',
            'pin',
            'private',
            'salt',
            'secret',
            'signature',
            'ssn',
            'token',
        ] : $sensitiveKeywords;
    }

    /**
     * Specify that this rule processes function-like nodes (functions,
     * methods, closures).
     *
     * This includes regular functions, class methods
     * (public/private/protected/static), constructors, destructors, and
     * closures.
     *
     * @return string The class name of nodes this rule processes
     */
    public function getNodeType(): string
    {
        return FunctionLike::class;
    }

    /**
     * Analyze a function-like node to detect parameters that might need
     * SensitiveParameter attribute.
     *
     * This method examines each parameter in the function/method to determine
     * if:
     *
     * 1. The parameter name contains any sensitive keywords (case-insensitive
     *    substring match)
     * 2. The parameter is not already protected by #[\SensitiveParameter]
     *    attribute
     * 3. The entire function is not protected by #[\SensitiveParameter]
     *    attribute
     *
     * Protection can be applied at two levels:
     *
     * - Function level: #[\SensitiveParameter] before the function declaration
     *   protects all parameters
     * - Parameter level: #[\SensitiveParameter] before individual parameter
     *   declarations
     *
     * @param Node $node The function/method node being analyzed
     * @param Scope $scope PHPStan scope context for the analysis
     *
     * @return RuleError[] Array of rule violations found for unprotected
     * sensitive parameters
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
                        'Parameter $%s in %s might contain sensitive information. Add the #[\\SensitiveParameter] attribute or ignore with `@phpstan-ignore sensitiveParameter.missing`.',
                        $paramName,
                        $functionName
                    ))
                        ->identifier('sensitiveParameter.missing')
                        ->build();
                    break;
                }
            }
        }

        return $errors;
    }
}
