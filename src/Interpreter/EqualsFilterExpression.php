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
    public function interpret(Context $context)
    {
        // TODO: Implement interpret() method.
    }
}