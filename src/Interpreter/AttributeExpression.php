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
    public function interpret(Context $context)
    {
        // TODO: Implement interpret() method.
    }
}