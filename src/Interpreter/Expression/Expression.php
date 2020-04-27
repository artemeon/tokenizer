<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Interpreter\ScimException;

interface Expression
{
    /**
     * @throws ScimException
     */
    public function interpret(ScimContext $context);
}
