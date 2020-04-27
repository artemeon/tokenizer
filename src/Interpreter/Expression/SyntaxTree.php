<?php

/*
 * This file is part of the Artemeon Core - Web Application Framework.
 *
 * (c) Artemeon <www.artemeon.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\ScimContext;

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