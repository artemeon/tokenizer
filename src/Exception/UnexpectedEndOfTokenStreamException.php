<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Exception;

class UnexpectedEndOfTokenStreamException extends TokenizerException
{
    public static function create(): self
    {
        return new self("End of token stream reached");
    }
}