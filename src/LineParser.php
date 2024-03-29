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
 * Class to apply regular expression on a line string
 */
class LineParser
{
    private string $line;
    private int $countUnparsedCharacters;
    private string $match = "";
    private int $lineNumber;
    private int $characterPosition = 0;
    private int $characterOffset = 0;

    private function __construct(string $line, int $number)
    {
        $this->line = $line;
        $this->lineNumber = $number;
        $this->countUnparsedCharacters = strlen($line);
    }

    /**
     * Named constructor to create an instance based on the given string and line number
     */
    public static function fromString(string $string, int $lineNumber): LineParser
    {
        return new self($string, $lineNumber);
    }

    /**
     * Apply's the given pattern on the current line string
     */
    public function applyPattern(string $pattern): bool
    {
        $this->match = '';

        if (preg_match($pattern, $this->line, $matches) !== 1) {
            return false;
        }

        $this->match = $matches[0];
        $this->line = substr($this->line, strlen($matches[0]));
        $this->countUnparsedCharacters = strlen($this->line);
        $this->characterPosition += $this->characterOffset;
        $this->characterOffset = strlen($matches[0]);

        return true;
    }

    /**
     * Returns the last match
     */
    public function getMatch(): string
    {
        return $this->match;
    }

    /**
     * Checks for unparsed characters
     */
    public function isResolved(): bool
    {
        return $this->countUnparsedCharacters === 0;
    }

    /**
     * Mark the current string as resolved
     */
    public function resolve(): void
    {
        $this->countUnparsedCharacters = 0;
    }

    /**
     * Returns the unmatched part of the current string
     */
    public function getUnmatched(): string
    {
        return $this->line;
    }

    /**
     * Returns the character position of the last match
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
}
