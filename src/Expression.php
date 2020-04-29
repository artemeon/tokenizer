<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer;

/**
 * Example Expression interface as described in the interpreter pattern
 */
interface Expression
{
    /**
     * Interpret current expression based on the given Context
     */
    public function interpret(Context $context): void;
}
