<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

class SubAttributeExpression implements Expression
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function interpret(Context $context)
    {
        // TODO: Implement interpret() method.
    }
}
