<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Interpreter\Expression\Expression;
use Artemeon\Tokenizer\Interpreter\Node\Node;
use SplObjectStorage;
use stdClass;
use UnexpectedValueException;

/**
 * ScimContext class based on the interpreter patten
 */
class ScimContext
{
    /** @var stdClass */
    private $jsonObject;

    /** @var mixed */
    private $currentData;

    /** @var Expression */
    private $lastExpression;

    /** @var Node */
    private $jsonNode;

    /** @var SplObjectStorage */
    protected $expressionStorage;

    public function __construct(stdClass $jsonObject)
    {
        $this->jsonObject = $jsonObject;
        $this->currentData = $this->jsonObject;
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

    /**
     * Return the node previously set by the last Expression
     */
    public function getNode(): Node
    {
        return $this->jsonNode;
    }

    /**
     * Sets the found note
     */
    public function setNode(Node $jsonNode): void
    {
        $this->jsonNode = $jsonNode;
    }

    /**
     * Return the current data reference, set by the previous Expression
     *
     * @return mixed
     */
    public function &getCurrentData()
    {
        return $this->currentData;
    }

    /**
     * Set the current data reference
     *
     * @param mixed $currentData
     */
    public function setCurrentData(&$currentData): void
    {
        $this->currentData = &$currentData;
    }

    /**
     * Checks if the given Expression is the last Expression
     */
    public function isLastExpression(Expression $expression): bool
    {
        return $this->lastExpression === $expression;
    }

    /**
     * Set the last Expression from the syntax tree
     */
    public function setLastExpression(Expression $lastExpression): void
    {
        $this->lastExpression = $lastExpression;
    }
}