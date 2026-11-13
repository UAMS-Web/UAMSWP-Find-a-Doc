<?php

/**
 * Gives `get_field('a_known_name')` the type its field group declares, so a template that reads a field does not
 * start from `mixed`.
 */

declare(strict_types=1);

namespace UamswpFindADoc\PhpStan;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\TypeStringResolver;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\Type;

/**
 * Answers from `phpstan/acf-field-types.php`, generated from `assets/json/acf-json` by
 * `scripts/generate-acf-field-types.php`. A call with a name that is not in the map, a selector that is not a
 * constant string, or formatting switched off (the third argument), keeps the stub's `mixed`.
 */
final class AcfReturnTypeExtension implements DynamicFunctionReturnTypeExtension
{
    /** @var array<string, string> */
    private readonly array $types;

    public function __construct(private readonly TypeStringResolver $resolver)
    {
        /** @var array<string, string> $types */
        $types = require __DIR__.'/acf-field-types.php';
        $this->types = $types;
    }

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return $functionReflection->getName() === 'get_field';
    }

    public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope): ?Type
    {
        $arguments = $functionCall->getArgs();

        if ($arguments === []) {
            return null;
        }

        // With formatting off ACF returns the stored value, which the map does not describe.
        if (isset($arguments[2]) && ! $scope->getType($arguments[2]->value)->isSuperTypeOf(new ConstantBooleanType(true))->yes()) {
            return null;
        }

        $selectors = $scope->getType($arguments[0]->value)->getConstantStrings();

        if (count($selectors) !== 1 || ! isset($this->types[$selectors[0]->getValue()])) {
            return null;
        }

        return $this->resolver->resolve($this->types[$selectors[0]->getValue()]);
    }
}
