<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Expression;

use Artemeon\Tokenizer\Context;

interface Expression
{
    public function interpret(Context $context);
}
