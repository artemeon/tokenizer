<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Exception;
use SplObjectStorage;
use UnexpectedValueException;

/**
 * Context base class as described in the interpreter pattern
 */
class Context
{
    /** @var SplObjectStorage */
    protected $expressionStorage;

    /** @var Exception */
    protected $previousExpresion;

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
        $this->previousExpresion = $expression;
        $this->expressionStorage->offsetSet($expression, $result);
    }

    /**
     * Return the result value of the given expression
     *
     * @throws UnexpectedValueException
     * @return mixed
     */
    public function getExpressionResult(Expression $expression)
    {
        return $this->expressionStorage->offsetGet($expression);
    }

    /**
     * Return the result value of the previous set expression
     *
     * @throws UnexpectedValueException
     * @return mixed
     */
    public function getPreviousExpressionResult()
    {
        return $this->expressionStorage->offsetGet($this->previousExpresion);
    }
}
