<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer;

/**
 * Token value object
 */
class Token
{
    /** @var string */
    private $type;

    /** @var string */
    private $value;

    /** @var int */
    private $lineNumber;

    /** @var int */
    private $characterPosition;

    public function __construct(string $name, string $value, int $lineNumber, int $position)
    {
        $this->type  = $name;
        $this->value = $value;
        $this->lineNumber = $lineNumber + 1;
        $this->characterPosition = $position;
    }

    /**
     * Return the character position
     */
    public function getCharacterPosition(): int
    {
        return $this->characterPosition;
    }

    /**
     * Returns the line number
     */
    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    /**
     * Returns the token type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the token value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the string length of the token value
     */
    public function getLength(): int
    {
        return strlen($this->value);
    }
}
