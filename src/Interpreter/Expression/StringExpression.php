<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\ScimContext;

class StringExpression implements Expression
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = str_replace('"', '', $value);
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $context->setExpressionResult($this, (string) $this->value);
    }
}
