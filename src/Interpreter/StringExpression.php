<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

class StringExpression implements Expression
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function interpret(Context $context)
    {
        // TODO: Implement interpret() method.
    }
}
