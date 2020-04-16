<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

class EqualsFilterExpression implements Expression
{
    /** @var string */
    private $attribute;

    /** @var Expression */
    private $valueExpression;

    public function __construct(string $attribute, Expression $valueExpression)
    {
        $this->attribute = $attribute;
        $this->valueExpression = $valueExpression;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = &$context->getCurrentData();

        if (is_array($data)) {
            foreach ($data as $index => &$row) {
                $context->setCurrentData($row);
                $this->valueExpression->interpret($context);
                $needle = $context->getExpressionResult($this->valueExpression);

                if (property_exists($row, $this->attribute)) {
                    $attribute = $row->{$this->attribute};

                    if ($attribute == $needle) {
                        $context->setExpressionResult($this, $row);
                        $context->concatQuery("[$index]");
                        return;
                    }
                }
            }
        }
    }
}