<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\Operation\Operation;
use Artemeon\Tokenizer\Interpreter\ScimContext;

class EqualsFilterExpression implements Expression
{
    /** @var string */
    private $attribute;

    /** @var Expression */
    private $valueExpression;

    /** @var Operation|null */
    private $operation;

    public function __construct(string $attribute, Expression $valueExpression, ?Operation $operation)
    {
        $this->attribute = $attribute;
        $this->valueExpression = $valueExpression;
        $this->operation = $operation;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = &$context->getCurrentData();

        if (is_array($data)) {
            foreach ($data as $index => &$row) {
                $this->valueExpression->interpret($context);
                $needle = $context->getExpressionResult($this->valueExpression);

                if (property_exists($row, $this->attribute)) {
                    $propertyValue = $row->{$this->attribute};

                    if ($propertyValue == $needle) {
                        if ($context->isLastExpression($this)) {
                            $this->operation->processMultiValuedAttribute($data, $index);
                            return;
                        }

                        $context->setCurrentData($row);
                        $context->setExpressionResult($this, $row);
                        return;
                    }
                }
            }
        }
    }
}