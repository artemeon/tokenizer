<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tokenizer\Expression;

use Artemeon\Tokenizer\Tokenizer\Context;

interface Expression
{
    public function interpret(Context $context);
}
