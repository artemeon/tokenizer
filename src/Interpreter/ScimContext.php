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
use Artemeon\Tokenizer\Interpreter\Operation\Operation;
use stdClass;

class ScimContext extends Context
{
    /** @var stdClass */
    private $jsonData;

    /** @var mixed */
    private $currentData;

    /** @var Expression */
    private $lastExpression;

    /** @var Operation */
    private $operation;

    public function __construct(string $jsonData, Operation $operation)
    {
        $this->operation = $operation;
        $this->jsonData = json_decode($jsonData);
        $this->currentData = $this->jsonData;

        parent::__construct();
    }

    /**
     * @param $propertyValue
     * @param $index
     * @param string $attributeName
     */
    public function setOperationData(&$propertyValue, $index = null, string $attributeName = '')
    {
        if (is_array($propertyValue)) {
            $index = $index ?? max(array_keys($propertyValue)) + 1;
            $this->operation->processMultiValuedAttribute($propertyValue, $index);
            return;
        }

        if (is_object($propertyValue)) {
            $this->operation->processComplexAttribute($attributeName, $propertyValue);
            return;
        }

        $this->operation->processSingleValuedAttribute($propertyValue);
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