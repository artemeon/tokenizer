<?php

/*
 * This file is part of the Artemeon Core - Web Application Framework.
 *
 * (c) Artemeon <www.artemeon.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Interpreter\Expression\Expression;
use stdClass;

class ScimContext extends Context
{
    /** @var stdClass */
    private $jsonData;

    /** @var mixed */
    private $currentData;

    /** @var Expression */
    private $lastExpression;

    /** @var JsonNode */
    private $jsonNode;

    public function __construct(stdClass $jsonObject)
    {
        $this->jsonData = $jsonObject;
        $this->currentData = $this->jsonData;

        parent::__construct();
    }

    /**
     * @return JsonNode
     */
    public function getJsonNode(): JsonNode
    {
        return $this->jsonNode;
    }

    public function setFoundNode(JsonNode $jsonNode)
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