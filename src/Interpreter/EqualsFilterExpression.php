<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

class EqualsFilterExpression implements Expression
{
    /** @var Expression */
    private $attributeExpression;

    /** @var Expression */
    private $valueExpression;

    public function __construct(Expression $attributeExpression, Expression $valueExpression)
    {
        $this->attributeExpression = $attributeExpression;
        $this->valueExpression = $valueExpression;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = $context->getCurrentData();

        if (is_array($data)) {
            foreach ($data as &$row) {
                $context->setCurrentData($row);
                $this->attributeExpression->interpret($context);
                $this->valueExpression->interpret($context);
                $attribute = $context->getExpressionResult($this->attributeExpression);
                $needle = $context->getExpressionResult($this->valueExpression);

                if ($attribute == $needle) {
                    $context->setCurrentData($row);
                    $context->setExpressionResult($this, $row);
                    return;
                }
            }
        }
    }
}