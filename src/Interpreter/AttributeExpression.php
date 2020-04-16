<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

class AttributeExpression implements Expression
{
    /** @var string */
    private $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = $context->getCurrentData();

        if (property_exists($data, $this->name)) {
            $attribute = &$data->{$this->name};
            $context->setExpressionResult($this, $attribute);
            $context->setCurrentData($attribute);

        }
    }
}