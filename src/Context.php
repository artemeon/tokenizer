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
use UnexpectedValueException;

/**
 * Example Context class as described in the interpreter pattern
 */
class Context
{
    protected SplObjectStorage $expressionStorage;

    public function __construct()
    {
        $this->expressionStorage = new SplObjectStorage();
    }

    /**
     * Set the result value of the given Expression
     *
     * @param mixed $result
     */
    public function setExpressionResult(Expression $expression, $result): void
    {
        $this->expressionStorage->offsetSet($expression, $result);
    }

    /**
     * Return the result value of the given expression
     *
     * @return mixed
     * @throws UnexpectedValueException
     */
    public function getExpressionResult(Expression $expression)
    {
        return $this->expressionStorage->offsetGet($expression);
    }
}
