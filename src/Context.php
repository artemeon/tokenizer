<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer;

use SplObjectStorage;

/**
 * Example Context class as described in the interpreter pattern
 */
class Context
{
    /** @var SplObjectStorage<Expression, mixed> $expressionStorage */
    private SplObjectStorage $expressionStorage;

    public function __construct()
    {
        $this->expressionStorage = new SplObjectStorage();
    }

    /**
     * Set the result value of the given Expression.
     */
    public function setExpressionResult(Expression $expression, mixed $result): void
    {
        $this->expressionStorage->offsetSet($expression, $result);
    }

    /**
     * Return the result value of the given expression.
     */
    public function getExpressionResult(Expression $expression): mixed
    {
        return $this->expressionStorage->offsetGet($expression);
    }
}
