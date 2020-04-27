<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Interpreter\Expression\Expression;
use Artemeon\Tokenizer\Interpreter\Node\FilterNode;
use Artemeon\Tokenizer\Interpreter\Node\Node;
use Artemeon\Tokenizer\Tokenizer\Context;
use stdClass;

class ScimContext extends Context
{
    /** @var stdClass */
    private $jsonData;

    /** @var mixed */
    private $currentData;

    /** @var Expression */
    private $lastExpression;

    /** @var FilterNode */
    private $jsonNode;

    public function __construct(stdClass $jsonObject)
    {
        $this->jsonData = $jsonObject;
        $this->currentData = $this->jsonData;

        parent::__construct();
    }

    /**
     * @return FilterNode
     */
    public function getJsonNode(): Node
    {
        return $this->jsonNode;
    }

    public function setFoundNode(Node $jsonNode)
    {
        $this->jsonNode = $jsonNode;
    }

    /**
     * @return stdClass
     */
    public function getJsonData(): stdClass
    {
        return $this->jsonData;
    }

    /**
     * @return mixed
     */
    public function &getCurrentData()
    {
        return $this->currentData;
    }

    /**
     * @param mixed $currentData
     */
    public function setCurrentData(&$currentData): void
    {
        $this->currentData = &$currentData;
    }

    /**
     * @return Expression
     */
    public function isLastExpression(Expression $expression): bool
    {
        return $this->lastExpression === $expression;
    }

    /**
     * @param Expression $lastExpression
     */
    public function setLastExpression(Expression $lastExpression): void
    {
        $this->lastExpression = $lastExpression;
    }
}