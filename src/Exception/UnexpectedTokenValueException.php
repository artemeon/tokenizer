<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Exception;

use Artemeon\Tokenizer\Token;

class UnexpectedTokenValueException extends TokenizerException implements TokenException
{
    /** @var Token */
    private $token;

    public function __construct(Token $token, string $message)
    {
        $this->token = $token;
        parent::__construct($message);
    }

    /**
     * Named constructor to create an instance based on the given token
     */
    public static function fromToken(Token $token)
    {
        $message = "Unexpected token value: " . $token->getValue();
        return new self($token, $message);
    }

    /**
     * @return Token
     */
    public function getToken(): Token
    {
        return $this->token;
    }
}
