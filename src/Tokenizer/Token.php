<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tokenizer;

class Token
{
    /** @var string */
    private $type;

    /** @var string */
    private $value;

    /** @var int */
    private $number;

    /** @var int */
    private $position;

    public function __construct(string $name, string $value, int $lineNumber, int $position)
    {
        $this->type  = $name;
        $this->value = $value;
        $this->number = $lineNumber + 1;
        $this->position = $position;
    }

    /**
     * Return the token type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Return the token value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Return the string length of the token value
     */
    public function getLength(): int
    {
        return strlen($this->value);
    }
}
