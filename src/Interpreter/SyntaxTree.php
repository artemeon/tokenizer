<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Interpreter\Expression\Expression;

/**
 * Collection class for scim Expression classes
 */
class SyntaxTree implements Expression
{
    /** @var Expression[] */
    private $expressions;

    /**
     * Named constructor to create an instance based on the given Expression array
     *
     * @param Expression[] $expressions
     */
    public static function fromArray(array $expressions): self
    {
        $instance = new self();

        foreach ($expressions as $expression) {
            $instance->addExpression($expression);
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $lastIndex = max(array_keys($this->expressions));
        $lastExpression = $this->expressions[$lastIndex];

        $context->setLastExpression($lastExpression);

        foreach ($this->expressions as $expression) {
            $expression->interpret($context);
        }

        return $context;
    }

    /**
     * Add a expresion
     */
    private function addExpression(Expression $expression): void
    {
        $this->expressions[] = $expression;
    }
}