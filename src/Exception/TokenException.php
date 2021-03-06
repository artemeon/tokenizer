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

interface TokenException
{
    /**
     * @return Token
     */
    public function getToken(): Token;
}
